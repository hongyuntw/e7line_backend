<?php

namespace App\Http\Controllers;

use App\BusinessConcatPerson;
use App\Customer;
use App\Exports\InvoicesExport;
use App\Exports\OrderExport;
use App\Order;
use App\OrderItem;
use App\Product;
use App\ProductDetail;
use App\ProductRelation;
use App\User;
use App\Welfare;
use App\WelfareStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Illuminate\Config;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static $order_item_status_names = ['尚未受理', '已收單', '已叫貨', '已交貨', '已出貨'];
    public static $order_status_names = ['未處理', '處理中', '貨已到', '已完成'];

    public static $payment_method_names = ['匯款', '貨到付款', '薪資帳戶扣款', '信用卡刷卡機制', 'LINEPay'];

    public function index(Request $request)
    {
        //
        $status = 0;
        $sortBy_text = ['建單日期', '收件日期'];
        $user_filter = -1;
        $status_filter = -1;
        $sortBy = 'create_date';
        $query = Order::query();
        $search_type = 0;
        $search_info = '';
        $code_filter = -1;
        $products = Product::all();
        $users = User::all();
        $product_id = -1;
        $product_detail_id = -1;
        $product_relation = null;
        $date_from = null;
        $date_to = null;

        //   get     sort
        if($request->has('sortBy')){
            $sortBy = $request->input('sortBy');
        }
//        user
        if($request->has('user_filter')){
            $user_filter = $request->input('user_filter');
        }
        if($user_filter>0) {
            $query->where('user_id','=',$user_filter);
        }
// status
        if ($request->has('status_filter')) {
            $status_filter = $request->input('status_filter');
        }
        if((int)$status_filter>=0){
            $query->where('orders.status', '=', (int)$status_filter);
        }
        if($request->has('code_filter')){
            $code_filter = $request->input('code_filter');
        }
        if($code_filter>=0){
            switch ($code_filter){
                case 0:
                    $query->whereNull('orders.code');
                    break;
                case 1:
                    $query->whereNotNull('orders.code');

                    break;
                default:
                    break;
            }
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
            $query->whereBetween('orders.'.$sortBy,[$date_from_addtime,$date_to_addtime]);
        }
        if ($request->has('search_type')) {
            $search_type = $request->query('search_type');
        }
        if ($search_type > 0) {
            $search_info = $request->query('search_info');
            switch ($search_type) {
                case 1:
                    $query->where('orders.no', 'like', "%{$search_info}%");
                    break;
                case 2:
                    $query->leftJoin('customers','customers.id','=','orders.customer_id');
                    $query->select('orders.*','customers.name');
                    $query->where(function ($query) use ($search_info) {
                        $query->where('customers.name', 'like', "%{$search_info}%")
                            ->orWhere('orders.other_customer_name', 'like', "%{$search_info}%");
                        return $query;

                    });
                    break;
                case 3:
                    $query->where("tax_id",'like',"%{$search_info}%");
                    break;
                case 4:
                    $query->leftJoin('business_concat_persons','business_concat_persons.id','=','orders.business_concat_person_id');
//                    dd($query->get());
                    $query->select('orders.*','business_concat_persons.name');
                    $query->where(function ($query) use ($search_info) {
                        $query->where('business_concat_persons.name', 'like', "%{$search_info}%")
                            ->orWhere('orders.other_concat_person_name', 'like', "%{$search_info}%");
                        return $query;
                    });
                    break;
                default:
                    break;
            }
        }







        $query->where('orders.is_deleted','=',0);
//        dd($sortBy);
        $query->orderBy('orders.'.$sortBy,'DESC');
        $orders = $query->paginate(15);



        $data = [
            'orders' => $orders,
            'order_status_names' => self::$order_status_names,
            'status_filter' => $status_filter,
            'product_id'=>$product_id,
            'product_detail_id'=>$product_detail_id,
            'user_filter'=>$user_filter,
            'users'=>$users,
            'sortBy'=>$sortBy,
            'sortBy_text'=>$sortBy_text,
            'date_from'=>$date_from,
            'date_to'=>$date_to,
            'code_filter'=>$code_filter,
        ];

        return view('orders.index', $data);

    }

    public function detail(Order $order)
    {
        $order_items = $order->order_items;
        $data = [
            'order' => $order,
            'order_items' => $order_items,
            'order_status_names' => self::$order_status_names,
            'payment_method_names' => self::$payment_method_names,
        ];
        return view('orders.detail', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $customers = Customer::all();
        $welfares = Welfare::all();
        $users = User::all();
        $products = Product::all();

        $data = [
            'customers' => $customers,
            'welfares' => $welfares,
            'users' => $users,
            'order_status_names' => self::$order_status_names,
            'payment_method_names' => self::$payment_method_names,
            'products' => $products,
        ];
        return view('orders.create', $data);


    }

    public function rules(Request $request)
    {
        // general rules
//        dd($request);
        $rules = [
            'customer_id' => 'required',
            'welfare_id' => 'required',
            'business_concat_person_id' => 'required',
            'tax_id' => 'numeric|digits_between:8,8|nullable',
            'payment_method' => 'required',
            'product_id' => 'required',
            'product_detail_id' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'product_id.*' => 'required|integer|min:1',
            'product_detail_id.*' => 'required|integer|min:1',
            'quantity.*' => 'required',
            'price.*' => 'required',
            'shipping_fee'=>'required',
        ];

        // conditional rules
        if ($request->input('customer_id') == -1) {
            $rules['other_customer_name'] = 'required';
        }
        if ($request->input('business_concat_person_id') == -1) {
            $rules['other_concat_person_name'] = 'required';
        }
        return $rules;
    }

    public function changeStatusBack(Request $request)
    {
        $order = Order::find($request->input('id'));
        $order->status = 0;
        $order->update();
        return "已將訂單#".$order->no."狀態改回未處理";
    }


    public function get_code(Order $order)
    {
//        dd($order);
        $base_url = \config('url.e7line_url');
        $api_path = $base_url . '/API/CreateOrderBySales.aspx';

        $memberNo = "";



        $OrderNoPrefix = 'HP';

        $data = [
            'Address' => $order->ship_to? $order->ship_to :'',
            'Paymethod' => self::$payment_method_names[$order->payment_method],
            'InvoiceNo' => $order->tax_id ? $order->tax_id : '',
            'Notice' => $order->note ?$order->note : '',
            'ShippingFee' => (integer)round($order->shipping_fee),
            'MemberNo' => $order->e7line_account?$order->e7line_account: '',

        ];
        //test


        $orderSubs = [];

////
        foreach ($order->order_items as $order_item) {
            $arr = [];
            $arr['ISBN'] = $order_item->product_relation->ISBN?$order_item->product_relation->ISBN:'';
            $arr['Qty'] = (integer)($order_item->quantity);
            $arr['ListPrice'] = (integer)round($order_item->price);
            $arr['SpecName'] = $order_item->spec_name?$order_item->spec_name : '';

            if($order_item->product_relation->ISBN){
                if($order_item->product_relation->ISBN == 'P1608310002' || $order_item->product_relation->ISBN == 'P1709150001'){
                    $OrderNoPrefix = 'EL';
                }
            }
            array_push($orderSubs, $arr);
        }
        $data['orderSubs'] = $orderSubs;
        $data['OrderNoPrefix'] = $OrderNoPrefix;
//        dump($data);
        $data_json = json_encode($data);
//        dd($data_json);

        $client = new \GuzzleHttp\Client();
        $result = $client->post($api_path, [
            'form_params' => [
                'e7lineOrder'=> $data_json
            ]
        ]);
//        dd($result);
        $resp  = $result->getBody()->getContents();
//        dump(($resp));
        $resp = json_decode($resp,true,2);
        if($resp['isScuess']==true){
            $order->code = $resp['orderNo'];
            $order->update_date = now();
            $order->update();
            Session::flash('alert', 'success');
            Session::flash('msg',$resp['message']);
        }
        else{
            Session::flash('alert', 'failed');
            Session::flash('msg',$resp['message']);
        }
//        dd($resp);
        return redirect()->back();
    }


    public function getTaxIds(Request $request)
    {

        $customer_id = $request->input('customer_id');
        $other_customer_name = $request->input('other_customer_name');


        $taxId = [];

        if($customer_id == -1){
            $orders = Order::where('other_customer_name','=',$other_customer_name)->where('is_deleted','=',0)->get();
            foreach($orders as $order){
                if($order->tax_id){
                    if(array_key_exists($order->tax_id,$taxId)){
                        $taxId[$order->tax_id] += 1;
                    }
                    else{
                        $taxId[$order->tax_id] = 1;
                    }
                }
            }
        }
        else{
            $orders = Order::where('customer_id','=',$customer_id)->where('is_deleted','=',0)->get();
            foreach($orders as $order){
                if($order->tax_id){
                    if(array_key_exists($order->tax_id,$taxId)){
                        $taxId[$order->tax_id] += 1;
                    }
                    else{
                        $taxId[$order->tax_id] = 1;
                    }
                }
            }
        }
        return $taxId;


    }

    public function get_e7line_account_info(Request $request)
    {
//        dump($base_url);
        $base_url = \config('url.e7line_url');
        $api_path = $base_url . '/API/GetMemberByCompany.aspx';
//        dd($api_path);
//        $api_path = 'https://www.e7line.com:8081/API/GetMemberByCompany.aspx';
        $search = $request->input('customer_info');
        $search = str_replace('台','臺',$search);

        $client = new \GuzzleHttp\Client();
        $result = $client->post($api_path, [
            'form_params' => [
                'searchText'=> $search,
            ]
        ]);
//        dd($result);
        $resp  = $result->getBody()->getContents();
        return $resp;
    }


//
    public function index_get_code(Request $request)
    {
//        id => msg
        $total_result = [];
        if($request->has('ids')){
            foreach ($request->input('ids')as $id){
                $order = Order::find($id);
//                dd($order);
                if($order->code){
                    $total_result[$order->no] = '已經拋單過了';
                    continue;

                }

                $base_url = \config('url.e7line_url');
                $api_path = $base_url . '/API/CreateOrderBySales.aspx';

                $OrderNoPrefix = 'HP';


                $data = [
                    'Address' => $order->ship_to? $order->ship_to :'',
                    'Paymethod' => self::$payment_method_names[$order->payment_method],
                    'InvoiceNo' => $order->tax_id ? $order->tax_id : '',
                    'Notice' => $order->note ?$order->note : '',
                    'ShippingFee' => (integer)round($order->shipping_fee),
                    'MemberNo' => $order->e7line_account?$order->e7line_account: '',
                ];
                $orderSubs = [];
                foreach ($order->order_items as $order_item) {
                    $arr = [];
                    $arr['ISBN'] = $order_item->product_relation->ISBN?$order_item->product_relation->ISBN:'';
                    $arr['Qty'] = (integer)($order_item->quantity);
                    $arr['ListPrice'] = (integer)round($order_item->price);
                    $arr['SpecName'] = $order_item->spec_name?$order_item->spec_name : '';
                    if($order_item->product_relation->ISBN){
                        if($order_item->product_relation->ISBN == 'P1608310002' || $order_item->product_relation->ISBN == 'P1709150001'){
                            $OrderNoPrefix = 'EL';
                        }
                    }
                    array_push($orderSubs, $arr);
                }
                $data['OrderNoPrefix'] = $OrderNoPrefix;
                $data['orderSubs'] = $orderSubs;
                $data_json = json_encode($data);
                $client = new \GuzzleHttp\Client();
                $result = $client->post($api_path, [
                    'form_params' => [
                        'e7lineOrder'=> $data_json
                    ]
                ]);
                $resp  = $result->getBody()->getContents();
//        dump(($resp));
                $resp = json_decode($resp,true,2);
                if($resp['isScuess']==true){
                    $order->code = $resp['orderNo'];
                    $order->update_date = now();
                    $order->update();
                    $total_result[$order->no] = $resp['message'];
                }
                else{
                    $total_result[$order->no] = $resp['message'];
                }

            }
            return $total_result;

        }
        return;
    }

    public function changeStatus2Success(Request $request)
    {
        $total_result = [];
        if($request->has('ids')){
            foreach ($request->input('ids')as $id){
                $order = Order::find($id);
                if($order->status !=2 ){
                    $total_result[$order->no] = '狀態非貨已到，無法變更為已完成。';
                }
                else{
                    //                3 = 已完成
                    $order->status = 3;
                    $order->update();
                    $total_result[$order->no] = '變更狀態成功。';

                }

            }
            return $total_result;
        }
        return;
    }

    public function orderSuccess(Order $order)
    {
        $order->status = 3;
        $order->update();
        return redirect()->back();
    }


    public function exportFromIndex(Request $request)
    {
        $total_result = [];
        if($request->has('ids')){
            foreach ($request->input('ids')as $id){
                $order = Order::find($id);
                return Excel::download(new OrderExport($order), $order->no.'.xlsx');
            }
            return $total_result;
        }
        return;
    }

    public function export(Order $order)
    {
//        dd($order);
        return Excel::download(new OrderExport($order), $order->no.'.xlsx');
    }


    public function validate_order_form(Request $request)
    {
//        return $request;
//        dd($request);
        return $this->validate($request, $this->rules($request));

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
//        dd($request);
//        $this->validate($request, $this->rules($request));

        $data = $request->all();
        $product_info = [
            'product_id' => $data['product_id'],
            'product_detail_id' => $data['product_detail_id'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'spec_name' => $data['spec_name'],
            'ISBN'=>$data['ISBN'],
        ];
        unset($data['_token']);
        if ($data['customer_id'] == -1) {
            unset($data['customer_id']);
        }
        if ($data['business_concat_person_id'] == -1) {
            unset($data['business_concat_person_id']);
        }
        unset($data['redirect_to']);
        unset($data['product_id']);
        unset($data['product_detail_id']);
        unset($data['quantity']);
        unset($data['price']);
        unset($data['spec_name']);
        unset($data['ISBN']);
        unset($data['e7line_info']);
        unset($data['e7line_customer_info']);
        $data['user_id'] = Auth::user()->id;
        $currentMonth = date('m');
        $this_month_data = Order::whereRaw('MONTH(create_date) = ?',[$currentMonth])->get();
        $no = date("y").date("m").str_pad(count($this_month_data)+1, 4, '0', STR_PAD_LEFT);
        $data['no'] = $no;
        $order = Order::create($data);

//  create order item

        $amount = 0;
        for ($i = 0; $i < count($product_info['product_id']); $i++) {
            $product_relation = ProductRelation::where('product_id', '=', $product_info['product_id'][$i])
                ->where('product_detail_id', '=', $product_info['product_detail_id'][$i])->first();
            $order_item = OrderItem::create([
                'order_id' => $order->id,
                'product_relation_id' => $product_relation->id,
                'quantity' => $product_info['quantity'][$i],
                'price' => $product_info['price'][$i],
                'spec_name' => $product_info['spec_name'][$i],
                'create_date' => now(),
                'update_date' => now(),
            ]);
            $amount += $product_info['price'][$i] * $product_info['quantity'][$i];
        }

        $order->amount = $amount;
        $order->create_date = now();
        $order->update_date = now();
        $order->update();

        return redirect()->route('orders.detail', $order->id);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order, Request $request)
    {
        //

        $customers = Customer::all();
        $welfares = Welfare::all();
        $users = User::all();
        $products = Product::all();
        $product_details = ProductDetail::all();
        $product_relations = ProductRelation::all();

        $order_items = $order->order_items;
        $data = [
            'customers' => $customers,
            'welfares' => $welfares,
            'users' => $users,
            'order_status_names' => self::$order_status_names,
            'payment_method_names' => self::$payment_method_names,
            'products' => $products,
            'order' => $order,
            'order_items' => $order_items,
            'product_relations' => $product_relations,
            'source_html' => $request['source_html']
        ];

        return view('orders.edit', $data);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
//        dd($request);
//        $this->validate($request, $this->rules($request));

        $data = $request->all();
        $product_info = [
            'product_id' => $data['product_id'],
            'product_detail_id' => $data['product_detail_id'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'spec_name' => $data['spec_name'],

        ];
        unset($data['_token']);
        $source_html = $request['source_html'];
        if ($data['customer_id'] == -1) {
            $order->customer_id = null;
            $order->update();
            unset($data['customer_id']);
        }
        else{
            $order->other_customer_name = null;

        }
        if ($data['business_concat_person_id'] == -1) {
            $order->business_concat_person_id = null;
            $order->update();
            unset($data['business_concat_person_id']);
        }
        else{
            $order->other_concat_person_name = null;
        }
        unset($data['redirect_to']);
        unset($data['product_id']);
        unset($data['product_detail_id']);
        unset($data['quantity']);
        unset($data['price']);
        unset($data['source_html']);
        unset($data['spec_name']);
        unset($data['e7line_info']);
        unset($data['ISBN']);
        unset($data['e7line_customer_info']);



        $order->update($data);

        $order_items = $order->order_items;

        $amount = 0;
//        原本的比較少，代表要新增
        if (count($order_items) < count($product_info['product_id'])) {
//            先更新舊的資料
            for ($i = 0; $i < count($order_items); $i++) {
                $product_relation = ProductRelation::where('product_id', '=', $product_info['product_id'][$i])
                    ->where('product_detail_id', '=', $product_info['product_detail_id'][$i])->first();
                $order_item = $order_items[$i];
                $order_item->product_relation_id = $product_relation->id;
                $order_item->price = $product_info['price'][$i];
                $order_item->quantity = $product_info['quantity'][$i];
                $order_item->spec_name = $product_info['spec_name'][$i];
                $order_item->update_date = now();
                $order_item->update();
                $amount += $product_info['price'][$i] * $product_info['quantity'][$i];
            }
//            新增剩餘的order item
            for ($i = count($order_items); $i < count($product_info['product_id']); $i++) {
                $product_relation = ProductRelation::where('product_id', '=', $product_info['product_id'][$i])
                    ->where('product_detail_id', '=', $product_info['product_detail_id'][$i])->first();
                $order_item = OrderItem::create([
                    'order_id' => $order->id,
                    'product_relation_id' => $product_relation->id,
                    'quantity' => $product_info['quantity'][$i],
                    'price' => $product_info['price'][$i],
                    'spec_name' => $product_info['spec_name'][$i],
                    'create_date' => now(),
                    'update_date' => now(),
                ]);
                $amount += $product_info['price'][$i] * $product_info['quantity'][$i];
            }
        } elseif (count($order_items) > count($product_info['product_id'])) {
//            原本比較多
//            先更新舊的
            for ($i = 0; $i < count($product_info['product_id']); $i++) {
                $product_relation = ProductRelation::where('product_id', '=', $product_info['product_id'][$i])
                    ->where('product_detail_id', '=', $product_info['product_detail_id'][$i])->first();
                $order_item = $order_items[$i];
                $order_item->product_relation_id = $product_relation->id;
                $order_item->price = $product_info['price'][$i];
                $order_item->quantity = $product_info['quantity'][$i];
                $order_item->spec_name = $product_info['spec_name'][$i];
                $order_item->update_date = now();
                $order_item->update();
                $amount += $product_info['price'][$i] * $product_info['quantity'][$i];
            }
//            把多的刪掉
            for ($i = count($product_info['product_id']); $i < count($order_items); $i++) {
                $order_item = $order_items[$i];
                $order_item->delete();
            }

        } else {
//            一樣多
            for ($i = 0; $i < count($order_items); $i++) {
                $product_relation = ProductRelation::where('product_id', '=', $product_info['product_id'][$i])
                    ->where('product_detail_id', '=', $product_info['product_detail_id'][$i])->first();
                $order_item = $order_items[$i];
                $order_item->product_relation_id = $product_relation->id;
                $order_item->price = $product_info['price'][$i];
                $order_item->quantity = $product_info['quantity'][$i];
                $order_item->spec_name = $product_info['spec_name'][$i];
                $order_item->update_date = now();
                $order_item->update();
                $amount += $product_info['price'][$i] * $product_info['quantity'][$i];
            }
        }
        $order->amount = $amount;
        $order->update_date = now();
        $order->update();
        return Redirect::to($source_html);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Order $order)
    {
        $order->is_deleted = 1;
        $order->update_date = now();
        $order->update();
        return redirect()->back();
    }

    public function delete_backto_index(Order $order)
    {
        $order->is_deleted = 1;
        $order->update_date = now();
        $order->update();
        return Redirect::route('orders.index');
    }


    public function get_customer_concat_persons(Request $request)
    {
        $customer_id = $request['customer_select_id'];
//        $welfare_status = WelfareStatus::where('customer_id','=',$customer_id)->get()->pluck('welfare_name','id');
        $concat_persons = BusinessConcatPerson::where('customer_id', '=', $customer_id)->pluck('name', 'id')->toArray();

        return $concat_persons;
    }


    public function get_customer_info(Request $request)
    {
        $customer_id = $request['customer_select_id'];
        $customer  = Customer::find($customer_id);
        $res = [
            'address'=>'',
            'phone_number'=>'',
        ];


        if($customer){
            $res['address'] = $customer->city . $customer->area . $customer->address;
            if($customer->phone_number){
                $res['phone_number'] = $customer->phone_number;
            }
        }
        return $res;
    }

    public function get_concat_person_info(Request $request)
    {
        $concat_person_id = $request['selected_concat_person_id'];
        $concat_person  = BusinessConcatPerson::find($concat_person_id);
        $res = [
            'email'=>'',
            'phone_number'=>'',
        ];


        if($concat_person){

            if($concat_person->phone_number){
                $res['phone_number'] = $concat_person->phone_number;
            }
            if($concat_person->email){
                $res['email'] = $concat_person->email;
            }
        }
        return $res;
    }


    public function get_product_details(Request $request)
    {
        $product_id = $request['product_id'];
        $product = Product::find($product_id);
        $product_relations = $product->product_relations;

        $arr = [];
        foreach ($product_relations as $product_relation) {
            $product_detail = $product_relation->product_detail;
            $arr[$product_detail->id] = array($product_detail->name, $product_detail->price);
        }
        return $arr;
    }

    public function get_product_details_price(Request $request)
    {
        $product_relation = ProductRelation::where('product_detail_id', '=', $request['product_detail_id'])
            ->where('product_id', '=', $request['product_id'])->first();
        return [
            'price' => $product_relation->price,
            'ISBN' => $product_relation->ISBN,
//            'ISBN' => '123',
        ];
    }


}
