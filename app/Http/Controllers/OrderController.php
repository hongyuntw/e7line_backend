<?php

namespace App\Http\Controllers;

use App\BusinessConcatPerson;
use App\Customer;
use App\Order;
use App\OrderItem;
use App\Product;
use App\ProductDetail;
use App\ProductRelation;
use App\User;
use App\Welfare;
use App\WelfareStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Session;



class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static $order_item_status_names = ['尚未受理', '已收單', '已叫貨', '已交貨', '已出貨'];
    public static $order_status_names = ['未處理', '處理中', '已完成'];

    public static $payment_method_names = ['匯款', '貨到付款', '薪資帳戶扣款', '信用卡刷卡機制', 'LINEPay'];

    public function index()
    {
        //
        $orders = Order::paginate(15);
        $data = [
            'orders' => $orders,
            'order_status_names' => self::$order_status_names,
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
        $rules = [
            'customer_id' => 'required',
            'welfare_id' => 'required',
            'business_concat_person_id' => 'required',
//            'phone_number'=>'required',
//            'email'=>'required',
            'e7line_account' => 'required|not_in:-1',
            'e7line_name' => 'required',
            'user_id' => 'required',
            'status' => 'required',
            'payment_method' => 'required',
            'product_id.*' => 'required',
            'product_detail_id.*' => 'required',
            'quantity.*' => 'required',
            'price.*' => 'required',
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


    public function get_code(Order $order)
    {
//        dd($order);
        $api_path = 'https://www.e7line.com:8081/API/CreateOrderBySales.aspx';

//        $data = [
//            'Address' => $order->ship_to,
//            'Paymethod' => self::$payment_method_names[$order->payment_method],
//            'InvoiceNo' => $order->tax_id,
//            'Notice' => $order->note,
//        'ShippingFee' => $order->shipping_fee,

//        ];
        //test
        $data = [
            'Address' => '',
            'Paymethod' => '貨到付款',
            'InvoiceNo' => '',
            'Notice' => '',
            'MemberNo' => 'DEMO@amd.com',
            'ShippingFee' => $order->shipping_fee,
        ];

//        if ($order->other_customer_name) {
//            $data['MemberNo'] = $order->other_customer_name;
//        } else {
//            $data['MemberNo'] = $order->customer->name;
//        }

        $orderSubs = [];
        $arr = [];
//            $arr['ISBN'] = $order_item->product_relation->ISBN;
        $arr['ISBN'] = 'S2004220006';
        $arr['Qty'] = 1;
        $arr['ListPrice'] = 260;
        $arr['SpecName'] = '粉色';
        array_push($orderSubs, $arr);
////
//        foreach ($order->order_items as $order_item) {
//            $arr = [];
////            $arr['ISBN'] = $order_item->product_relation->ISBN;
//            $arr['ISBN'] = 'P1903270016';
//            $arr['Qty'] = $order_item->quantity;
//            $arr['ListPrice'] = $order_item->price;
//            $arr['SpecName'] = $order->spec_name;
//            array_push($orderSubs, $arr);
//        }
        $data['orderSubs'] = $orderSubs;
        $data_json = json_encode($data);
//        dump($data_json);
//        dd(gettype($data_json));

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
        $this->validate($request, $this->rules($request));

        $data = $request->all();
        $product_info = [
            'product_id' => $data['product_id'],
            'product_detail_id' => $data['product_detail_id'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'spec_name' => $data['spec_name'],
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
        $this->validate($request, $this->rules($request));

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
        unset($data['source_html']);
        unset($data['spec_name']);

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


    public function get_customer_concat_persons(Request $request)
    {
        $customer_id = $request['customer_select_id'];
//        $welfare_status = WelfareStatus::where('customer_id','=',$customer_id)->get()->pluck('welfare_name','id');
        $concat_persons = BusinessConcatPerson::where('customer_id', '=', $customer_id)->pluck('name', 'id')->toArray();

        return $concat_persons;
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
