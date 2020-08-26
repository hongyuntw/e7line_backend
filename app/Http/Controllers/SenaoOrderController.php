<?php

namespace App\Http\Controllers;

use App\BusinessConcatPerson;
use App\Exports\InvoicesExport;
use App\Exports\OrderExport;
use App\Exports\SenaoOrderExport;
use App\Exports\SenaoOrdersExport;
use App\Imports\SenaoOrdersImport;
use App\Order;
use App\Product;
use App\SenaoOrder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Session;


class SenaoOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static $senao_order_status_names = ['無','完成出貨','延遲出貨','無法出貨','退貨'];

    public function index(Request $request)
    {
        //
        $status = 0;
        $sortBy_text = ['訂單日期'];
        $user_filter = -1;
        $status_filter = 'All';
        $sortBy = 'create_date';
        $query = SenaoOrder::query();
        $search_type = 0;
        $search_info = '';

        $date_from = null;
        $date_to = null;

        //   get     sort
        if($request->has('sortBy')){
            $sortBy = $request->input('sortBy');
        }
////        user
//        if($request->has('user_filter')){
//            $user_filter = $request->input('user_filter');
//        }
//        if($user_filter>0) {
//            $query->where('user_id','=',$user_filter);
//        }
// status
        if ($request->has('status_filter')) {
            $status_filter = $request->input('status_filter');
        }
        if($status_filter != 'All'){
            $query->where('senao_orders.status', '=', $status_filter);
        }

        //        date filter
        if($request->has('date_from')){
            $date_from = $request->input('date_from');
        }
        if($request->has('date_to')){
            $date_to = $request->input('date_to');
        }
        if($date_from != null && $date_to != null){
            $date_from_addtime = $date_from." 00:00:00";
            $date_to_addtime = $date_to. " 23:59:59";
            $query->whereBetween('senao_orders.create_date',[$date_from_addtime,$date_to_addtime]);
        }
        if ($request->has('search_type')) {
            $search_type = $request->query('search_type');
        }
        if ($search_type > 0) {
            $search_info = $request->query('search_info');
            switch ($search_type) {
                case 1:
                    $orders_ids = Order::where('no','like',"%{$search_info}%")->pluck('id')->toArray();

                    $query->whereIn('senao_orders.order_id', $orders_ids);
                    break;
                case 2:
                    $query->where('senao_orders.seq_id', 'like', "%{$search_info}%");
                    break;
                case 3:
                    $query->where("senao_orders.receiver",'like',"%{$search_info}%");
                    break;
                case 4:
                    $query->where("senao_orders.senao_isbn",'like',"%{$search_info}%");
                    break;
                case 5:
                    $query->where("senao_orders.shipment_code",'like',"%{$search_info}%");
                    break;
                default:
                    break;
            }
        }


//        $query->where('orders.is_deleted','=',0);
        $query->orderBy('senao_orders.'.$sortBy,'DESC');
        $senao_orders = $query->paginate(15);



        $data = [
            'senao_orders' => $senao_orders,
            'status_filter' => $status_filter,
            'senao_order_status_names' => self::$senao_order_status_names,
            'sortBy'=>$sortBy,
            'sortBy_text'=>$sortBy_text,
            'date_from'=>$date_from,
            'date_to'=>$date_to,
        ];

        return view('senao_orders.index', $data);

    }

    function containsOnlyNull($input)
    {
        return empty(array_filter($input, function ($a) { return $a !== null;}));
    }


    public function import(Request $request)
    {

        if($request->file('file') == null){
            $msg = '必須上傳檔案';
            \Illuminate\Support\Facades\Session::flash('msg',$msg);
            return redirect()->back();
        }

        $extension = $request->file->getClientOriginalExtension();
        if(!in_array($extension, ['csv', 'xls', 'xlsx'])){
            $msg = '檔案必需為excel格式(副檔名為csv,xls,xlsx)';
            Session::flash('msg',$msg);
            return redirect()->back();
        }


        try{
            $import = new SenaoOrdersImport();
            Excel::import($import, request()->file('file'));
            $rows = $import->getRows()->toArray();
            $rules = [
                'user_name' => 'required|in:'.User::all()->implode('name', ','),
                'name' => 'required|unique:customers',
                'tax_id' => 'numeric|digits_between:8,8|nullable',
                'phone_number' => 'max:20|nullable',
                'fax_number' => 'max:20|nullable',
                'address' => 'max:50|nullable',
                'capital' => 'max:25|nullable',
                'city' => 'required',
                'area' => 'required',
                'scales' => 'nullable|integer'
            ];
            $error_messages = [
                'user_name.in' => '找不到業務名字',
                'name.unique' => '客戶名稱重複',
                'tax_id.*' => '統編格式錯誤',
                'fax_number.*'=>'傳真最長20碼',
                'address.*'=>'地址最長50碼',
                'capital.*'=>'傳真最長25',
                'user_name.required' => '業務欄位沒填喔',
                'name.required' => '客戶名稱欄位沒填喔',
                'city.required' => '城市欄位沒填喔',
                'area.required' => '地區欄位沒填喔',
                'scales.*' => '規模請填整數喔',
            ];
            array_shift($rows);

            $msgs = [];
            $msg = '';
            $index = 1;
            $success_count = 0;
            foreach($rows as $row){
                if($this->containsOnlyNull($row)){
                    continue;
                }

                $rename_row = [
                    'seq_id' => $row[0],
                    'no' => $row[1],
                    'create_date' =>$row[2],
                    'pay_date'=>$row[3],
                    'senao_isbn'=>$row[4],
                    'supplier_isbn'=>$row[5],
                    'product_name'=>$row[6],
                    'color'=>$row[7],
                    'attribute_name'=>$row[8],
                    'attribute_value'=>$row[9],
                    'quantity'=>$row[10],
                    'price'=>$row[11],
                    'receiver'=>$row[12],
                    'phone1'=>$row[13],
                    'phone2'=>$row[14],
                    'cellphone'=>$row[15],
                    'address'=>$row[16],
                    'status'=>$row[17],
                    'shipment_code'=>$row[18],
                    'shipment_company'=>$row[19],
                    'reason'=>$row[20],
                ];

//                find if seq id already exist in db
                $seano_order = SenaoOrder::where('seq_id','=',$rename_row['seq_id'])->first();
                if(is_null($seano_order)){
//                    create senao order for that
                    $newSenaoOrder = SenaoOrder::create($rename_row);
                    $newSenaoOrder->create_date = now();
                    $newSenaoOrder->update_date = now();
                    $newSenaoOrder->update();

//                    create e7line order for that
                    $currentMonth = date('m');
                    $this_month_data = Order::whereRaw('MONTH(create_date) = ?',[$currentMonth])->get();
                    $no = date("y").date("m").str_pad(count($this_month_data)+1, 4, '0', STR_PAD_LEFT);
                    $newOrder = Order::create([
                        'user_id' => Auth::user()->id,
                        'other_customer_name' => '神腦',
                        'other_concat_person_name' => $rename_row['receiver'],
                        'status'=> 0,
                        'phone_number'=>$rename_row['cellphone'],
                        'ship_to' => $rename_row['address'],
                        'is_paid' => 0,
                        'create_date'=>now(),
                        'update_date'=>now(),
                        'welfare_id' => 1,
                        'no'=> $no
                    ]);
//                    set relation
                    $newSenaoOrder->order_id = $newOrder->id;
                    $newSenaoOrder->update();
                    $newOrder->senao_order_id = $newSenaoOrder->id;
                    $newOrder->update();
//                    create order item for new order.....

                    $msg = '新增神腦訂單編號' . $rename_row['seq_id'] . '成功';
                    array_push($msgs,$msg);

                }
                else{
//                    update that
                    $seano_order->update($rename_row);
                    $seano_order->update_date = now();
                    $seano_order->update();

//                    update in our order......
                    $msg = '更新神腦訂單編號' . $rename_row['seq_id'] . '成功';
                    array_push($msgs,$msg);
                }

//                $validator = Validator::make($rename_row,$rules,$error_messages);
//                if ($validator->fails()) {
//                    $msg = '第'.$index.'筆資料新增失敗, ';
//                    foreach ($validator->errors()->all() as $error){
//                        $msg .= $error . ' ,';
//                    }
//                    array_push($msgs,$msg);
//                }
//                else{
//                    $user = User::where('name','=',$rename_row['user_name'])->first();
//                    $newCustomer = Customer::create([
//                        'name'=>$rename_row['name'],
//                        'status' => 1,
//                        'user_id'=>$user->id,
//                        'tax_id' => $rename_row['tax_id'],
//                        'capital' => $rename_row['capital'],
//                        'scales' =>  $rename_row['scales'],
//                        'city' => $rename_row['city'],
//                        'area' => $rename_row['area'],
//                        'phone_number' => $rename_row['phone_number'],
//                        'fax_number' => $rename_row['fax_number'],
//                        'address' => $rename_row['address'],
//                        'note' => $rename_row['note'],
//                        'active_status'=> 0,
//                        'already_set_sales' => $user->id == 1? 0 : 1,
//                        'is_deleted'=>0,
//                        'create_date' => now(),
//                        'update_date' => now(),
//                    ]);
//                    if($newCustomer -> user_id > 1){
//                        $newCustomer-> set_sales_date = now();
//                        $newCustomer ->update();
//                    }
//
//
//                    $this->addWelfareStatus($newCustomer);
//                    $success_count ++;
//                }
//                $index ++;
            }


//            array_push($msgs,'成功新增'.$success_count.'筆客戶');
            Session::flash('msgs',$msgs);
            return redirect()->back();

        }
        catch (\Exception $exception){
            dd($exception);
            $msg = '格式錯誤！請檢查檔案格式是否正確';
            Session::flash('msg',$msg);
            return redirect()->back();
        }
    }


    public function set_status_to_return(Request $request)
    {

        $data = [];

        $ids = $request->input('ids');
        foreach ($ids as $id){
            $senao_order = SenaoOrder::find($id);
            $senao_order->status = '退貨';
            $senao_order->update_date = now();
            $senao_order->update();
            $data[$senao_order->seq_id] = '變更狀態成功';
        }
        return $data;



    }

    public function export(Request $request)
    {
        $id_string = $request->input('ids');
        $ids  = explode(",", $id_string);
        $senao_orders = SenaoOrder::whereIn('id', $ids)->get();
//        $filename = date("D M d, Y G:i");
        $response = Excel::download(new SenaoOrdersExport($senao_orders), 'senaoOrders.xlsx' );

        return $response;
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
