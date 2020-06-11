<?php

namespace App\Http\Controllers;

use App\BusinessConcatPerson;
use App\ConcatRecord;
use App\Customer;
use App\Exports\CustomerTemplateExport;
use App\Exports\OrderExport;
use App\Imports\CustomersImport;
use App\Status;
use App\User;
use App\Welfare;
use App\WelfareCompany;
use App\WelfareDetail;
use App\WelfareStatus;
use App\WelfareType;
use App\WelfareTypeName;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class CustomersController extends Controller
{
//    private static $already_init = false;
//
//    public function init()
//    {
//        if(self::$already_init==false){
//            $welfare_codes = ['W001', 'W002', 'W003', 'W004', 'W005', 'W006', 'W007', 'W008', 'W009'];
//            $welfare_names = ['春節', '尾牙', '端午', '51勞動', '中秋', '生日', '電影', '旅遊', '其他'];
//            $customers = Customer::all();
//            foreach($customers as $customer){
//                if(count($customer->welfarestatus)==0){
//                    foreach (range(0, count($welfare_names) - 1) as $id) {
//                        \App\WelfareStatus::create([
//                            'customer_id' => $customer->id,
//                            'welfare_code' => $welfare_codes[$id],
//                            'welfare_name' => $welfare_names[$id],
//                            'track_status' => 1,
//                            'welfare_id' => $id,
//                            'create_date' => $customer->create_date,
//                            'update_date' => $customer->create_date,
//                        ]);
//                    }
//                }
//            }
//            self::$already_init = true;
//        }
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index(Request $request, User $user)
    {
//        if(self::$already_init==false){
//            self::init();
//        }

//        dd($request);
        $sortBy_text = ['創建日期', '縣市', '地區', '業務名稱', '狀態'];
        $status_text = ['---', '陌生', '重要', '普通', '潛在', '無效'];
        $status_filter = 0;
        $sortBy = 'create_date';
        $query = Customer::query();
//        0 means show all customers
//        1 means show my customers
        $user_filter = 0;
        $search_type = 0;
        $search_info = '';
        $query->where('is_deleted','=',0);


//        dd($query);
//        $customers = Customer::orderBy($sortBy, 'DESC')->paginate(15);

        if ($request->has('user_filter')) {
            $user_filter = $request->query('user_filter');
        }
        if ($user_filter > 0) {
            switch ($user_filter) {
                case 1:
                    $query->where('user_id', '=', Auth::user()->id);
                    break;
                case 2:
                    $query->where('user_id', '=', 1);
                    break;
                default:
                    break;
            }
        }

        if ($request->has('status_filter')) {
            $status_filter = $request->query('status_filter');
            $query->where(function ($query) use ($status_filter) {
                foreach ($status_filter as $s) {
                    if ($s > 0) {
                        $query->orWhere('status', '=', $s);
                    }
                }
                return $query;
            });
        }

        if ($request->has('search_type')) {
            $search_type = $request->query('search_type');
        }
        if ($search_type > 0) {
            $search_info = $request->query('search_info');
            switch ($search_type) {
                case 1:
                    $query->where('name', 'like', "%{$search_info}%");
                    break;
                case 2:
                    $query->where(function ($query) use ($search_info) {
                        $query->where('city', 'like', "%{$search_info}%")
                            ->orWhere('area', 'like', "%{$search_info}%");
                        return $query;

                    });
                    break;
                default:
                    break;
            }
        }
        if ($request->has('sortBy')) {
            $sortBy = $request->query('sortBy');
            foreach ($sortBy as $q) {
                $query->orderBy($q);
            }
        } else {
            $query->orderBy($sortBy, 'DESC');
        }


        $customers = $query->paginate(15);

        $data = [
            'customers' => $customers,
            'sortBy' => $sortBy,
            'user_filter' => $user_filter,
            'sortBy_text' => $sortBy_text,
            'status_filter' => $status_filter,
            'status_text' => $status_text,
        ];
        return view('customers.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $status_text = ['---', '陌生', '重要', '普通', '潛在', '無效'];
        $users = User::all();
        $data = [
            'status_text' => $status_text,
            'users' => $users,
        ];

        return view('customers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $this->validate($request, [
            'user_id' => 'required',
            'name' => 'required|unique:customers',


            'tax_id' => 'numeric|digits_between:8,8|nullable',
            'phone_number' => 'max:20|nullable',
            'fax_number' => 'max:20|nullable',
            'address' => 'max:50|nullable',
            'capital' => 'max:25|nullable',

            'status' => 'required',
            'city' => 'required',
            'area' => 'required',
            'active_status' => 'required',
        ]);
//        twzipcode 會包含zipcode 要移除掉
        $request_data = $request->all();
        unset($request_data['zipcode']);

        if($request->has('redirect_to')){
            unset($request_data['redirect_to']);
        }

        if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
        }

//        user choose the user
        if ($user_id > 1) {
            $request_data['already_set_sales'] = 1;
            $request_data['set_sales_date'] = now();
        }


        $customer = Customer::create($request_data);
        $customer->create_date = now();
        if($request_data['active_status']==1){
            $customer->active_time = now();
        }

        $customer->update();

        $this->addWelfareStatus($customer);


//        $welfare_codes = ['W001', 'W002', 'W003', 'W004', 'W005', 'W006', 'W007', 'W008', 'W009'];
//        $welfare_names = ['春節', '尾牙', '端午', '51勞動', '中秋', '生日', '電影', '旅遊', '其他'];
//
//        foreach (range(0, count($welfare_names) - 1) as $id) {
//            \App\WelfareStatus::create([
//                'customer_id' => $customer->id,
//                'welfare_code' => $welfare_codes[$id],
//                'welfare_name' => $welfare_names[$id],
//                'track_status' => 1,
//                'welfare_id' => $id,
//                'create_date' => now(),
//                'update_date' => now(),
//            ]);
//        }


        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
        $users = User::all();
        $status_text = ['---', '陌生', '重要', '普通', '潛在', '無效'];

        $data = [
            'users' => $users,
            'customer' => $customer,
            'status_text' => $status_text,
        ];
        return view('customers.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer,Request $request)
    {

//        dump($page);
//        dump($customer);
//        dump($request->all());


//        $customer = Customer::find($request->input('customer_id'));
        $status_text = ['---', '陌生', '重要', '普通', '潛在', '無效'];
        $users = User::all();
        $data = [
            'customer' => $customer,
            'status_text' => $status_text,
            'users' => $users,
        ];
        foreach ($request->all() as $key=>$value){
            $data[$key] = $value;
        }
//        dump($data);
        return view('customers.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {

        $user_id = $customer->user->id;

        $this->validate($request, [
            'name' => 'required|unique:customers,name,' . $customer->id,
            'active_status' => 'required',
            'status' => 'required',
            'city' => 'required',
            'area' => 'required',

            'capital' => 'max:25|nullable',
            'tax_id' => 'numeric|digits_between:8,8|nullable',
            'phone_number' => 'max:20|nullable',
            'fax_number' => 'max:20|nullable',
            'address' => 'max:50|nullable',
        ]);

        if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
        }

        $to_be_update_data = $request->all();
//        user choose the user
        if ($user_id > 1) {
            $to_be_update_data['user_id'] = $user_id;
            $to_be_update_data['already_set_sales'] = 1;
            $to_be_update_data['set_sales_date'] = now();
        }

        $to_be_update_data['update_date'] = now();
        unset($to_be_update_data['zipcode']);

        if($request->has('source_html')){
            unset($to_be_update_data['source_html']);
        }
        if($to_be_update_data['active_status']==1 && $customer->active_status ==0 ){
            $to_be_update_data['active_time'] = now();
        }



        $customer->update($to_be_update_data);
        if($request->has('source_html')){
            return Redirect::to($request->input('source_html'));
        }
        else{
            return redirect()->route('customers.index');

        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
        $customer->delete();

        return redirect()->route('customers.index');
    }


    public function delete(Customer $customer)
    {
//        dd($customer);
        $customer->is_deleted = 1;
        $customer->delete_time = now();
        $customer->update();

        return redirect()->back();
    }

    public function delete_back_to_index(Customer $customer)
    {
        dd($customer);
        $customer->is_deleted = 1;
        $customer->delete_time = now();
        $customer->update();

        return redirect()->route('customers.index');
    }


    public function record(Customer $customer)
    {


        $business_concat_persons = $customer->business_concat_persons()->orderBy('create_date', 'DESC')->get();
        $welfarestatus = $customer->welfarestatus->sortBy('welfare_id');
//        dd($welfarestatus);

        $concat_records = $customer->concat_records()->orderBy('update_date', 'DESC')->paginate(10);


        $welfare_type_names = WelfareTypeName::all();

        $status_text = ['---', '陌生', '重要', '普通', '潛在', '無效'];


        $data = [
            'customer' => $customer,
            'business_concat_persons' => $business_concat_persons,
            'welfarestatus' => $welfarestatus,
            'concat_records' => $concat_records,
            'welfare_type_names' => $welfare_type_names,
            'status_text' => $status_text,
        ];

        return view('customers.record', $data);
    }

    public function delete_welfare_type(Request $request)
    {
        $welfare_type = WelfareType::find($request['type_id']);
        $welfare_type->delete();
        return response()->json([
            'success' => 'delete success!',
        ]);

    }


    public function update_status(Request $request)
    {
        $customer = Customer::find($request['customer_id']);
        $customer->status = $request['customer_status'];
        $customer->active_status = $request['active_status'];
        $customer->note = $request['note'];
        $customer->update();

        return response()->json([
            'success' => 'update status success!',
        ]);
    }


    public function add_concat_person(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:30',
            'email' => 'email|nullable',
        ]);

        $is_left = false;
        $data = array(
            'customer_id' => $request['customer_id'],
            'name' => $request['name'],
            'phone_number' => $request['phone_number'],
            'extension_number' => $request['extension_number'],
            'email' => $request['email'],
            'create_date' => now(),
            'update_date' => now(),
            'is_left' => $is_left,
        );

        BusinessConcatPerson::insert($data);
        return response()->json([
            'success' => 'success',
        ]);

    }

    public function add_concat_record(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:2|min:0',
            'track_date' => 'nullable|date|after:' . now(),
            'development_content' => 'required',
        ]);


        if ($request['track_date'] != '') {
            $track_date = $request['track_date'];
        } else {
            $track_date = null;
        }

        $user_id = Auth::user()->id;
        $data = array(
            'customer_id' => $request['customer_id'],
            'user_id' => $user_id,
            'status' => $request['status'],
            'development_content' => $request['development_content'],
            'track_content' => $request['track_content'],
            'track_date' => $track_date,
            'create_date' => now(),
            'update_date' => now(),
        );


        ConcatRecord::insert($data);
        return response()->json([
            'success' => 'success',
        ]);
    }

    public function update_concat_person(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:30',
            'is_left' => 'required|min:0|max:1',
            'email' => 'email|nullable',
        ]);
        $concat_person_id = $request['concat_person_id'];
        $concat_person = BusinessConcatPerson::find($concat_person_id);
        $concat_person->want_receive_mail = $request['want_receive_mail'];
        $concat_person->name = $request['name'];
        $concat_person->phone_number = $request['phone_number'];
        $concat_person->extension_number = $request['extension_number'];
        $concat_person->email = $request['email'];
        $concat_person->is_left = $request['is_left'];
        $concat_person->update_date = now();
        $concat_person->update();
        return response()->json([
            'success' => 'update concat person info success',
        ]);

    }

    public function update_concat_record(Request $request)
    {
//        dump(now());
//        dd($request['track_date']);
        $this->validate($request, [
            'track_date' => 'nullable|date|after:' . now(),
        ]);
        $concat_record = ConcatRecord::find($request['concat_record_id']);
        $concat_record->status = $request['record_status'];
        $concat_record->development_content = $request['development_content'];
        $concat_record->track_content = $request['track_content'];
        $concat_record->track_date = $request['track_date'];
        $concat_record->user_id = Auth::user()->id;
        $concat_record->update();
        return response()->json([
            'success' => 'update concat record success',
        ]);

    }

    public function delete_concat_record(Request $request)
    {
        $concat_record = ConcatRecord::find($request['concat_record_id']);
        $concat_record->is_deleted = 1;
        $concat_record->update();
        return response()->json([
            'success' => 'update concat record success',
        ]);
    }

    public function add_welfare_types(Request $request)
    {
        $this->validate($request, [
            'welfare_status_id' => 'required',
            'welfare_code' => 'required',
            'budget' => 'nullable'

        ]);
        if ($request['welfare_code'] > 0) {
//        這邊再新增一種新的福利類別 for 此客戶的此status
            $data = array(
                'welfare_type_name_id' => $request['welfare_code'],
                'welfare_status_id' => $request['welfare_status_id'],
                'welfare_type_company_relation_id' => 1,
            );
            WelfareType::insert($data);

        }
//        新增note
        $welfare_status = WelfareStatus::find($request['welfare_status_id']);
        $welfare_status->note = $request['note'];
        $welfare_status->budget = $request['budget'];
        $welfare_status->update();
        return response()->json([
            'success' => 'success',
        ]);


    }
    function containsOnlyNull($input)
    {
        return empty(array_filter($input, function ($a) { return $a !== null;}));
    }


    public function import(Request $request)
    {

        if($request->file('file') == null){
            $msg = '必須上傳檔案';
            Session::flash('msg',$msg);
            return redirect()->back();
        }

        $extension = $request->file->getClientOriginalExtension();
        if(!in_array($extension, ['csv', 'xls', 'xlsx'])){
            $msg = '檔案必需為excel格式(副檔名為csv,xls,xlsx)';
            Session::flash('msg',$msg);
            return redirect()->back();
        }


        try{
            $import = new CustomersImport;
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
                    'name' => $row[0],
                    'user_name' => $row[1],
                    'tax_id' =>$row[2],
                    'capital'=>$row[3],
                    'scales'=>$row[4],
                    'city'=>$row[5],
                    'area'=>$row[6],
                    'address'=>$row[7],
                    'phone_number'=>$row[8],
                    'fax_number'=>$row[9],
                    'note'=>$row[10],
                ];

                $validator = Validator::make($rename_row,$rules,$error_messages);
                if ($validator->fails()) {
                    $msg = '第'.$index.'筆資料新增失敗, ';
                    foreach ($validator->errors()->all() as $error){
                        $msg .= $error . ' ,';
                    }
                    array_push($msgs,$msg);
                }
                else{
                    $user = User::where('name','=',$rename_row['user_name'])->first();
                    $newCustomer = Customer::create([
                        'name'=>$rename_row['name'],
                        'status' => 1,
                        'user_id'=>$user->id,
                        'tax_id' => $rename_row['tax_id'],
                        'capital' => $rename_row['capital'],
                        'scales' =>  $rename_row['scales'],
                        'city' => $rename_row['city'],
                        'area' => $rename_row['area'],
                        'phone_number' => $rename_row['phone_number'],
                        'fax_number' => $rename_row['fax_number'],
                        'address' => $rename_row['address'],
                        'note' => $rename_row['note'],
                        'active_status'=> 0,
                        'already_set_sales' => $user->id == 1? 0 : 1,
                        'is_deleted'=>0,
                        'create_date' => now(),
                        'update_date' => now(),
                    ]);
                    if($newCustomer -> user_id > 1){
                        $newCustomer-> set_sales_date = now();
                        $newCustomer ->update();
                    }


                    $this->addWelfareStatus($newCustomer);
                    $success_count ++;
                }
                $index ++;
            }


            array_push($msgs,'成功新增'.$success_count.'筆客戶');
            Session::flash('msgs',$msgs);
            return redirect()->back();


        }
        catch (\Exception $exception){
            $msg = '格式錯誤！請檢查檔案格式是否正確';
            Session::flash('msg',$msg);
            return redirect()->back();
        }
    }


    public function addWelfareStatus(Customer $customer)
    {
        $welfare_codes = ['W001', 'W002', 'W003', 'W004', 'W005', 'W006', 'W007', 'W008', 'W009'];
        $welfare_names = ['春節', '尾牙', '端午', '51勞動', '中秋', '生日', '電影', '旅遊', '其他'];
        foreach (range(0, count($welfare_names) - 1) as $id) {
            \App\WelfareStatus::create([
                'customer_id' => $customer->id,
                'welfare_code' => $welfare_codes[$id],
                'welfare_name' => $welfare_names[$id],
                'track_status' => 1,
                'welfare_id' => $id,
                'create_date' => now(),
                'update_date' => now(),
            ]);
        }
    }

    public function downloadTemplate()
    {
//        dd(123);
        return Excel::download(new CustomerTemplateExport(), '客戶資料格式參考.xlsx');

    }
}
