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
use Illuminate\Validation\Rule;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static $status_names = ['尚未受理','已收單','已叫貨','已交貨','已出貨'];
    public static $payment_method_names =['貨到/匯款','薪資','刷卡','line'];

    public function index()
    {
        //
        $orders = Order::paginate(15);
        $data=[
            'orders'=>$orders,
            'status_names'=>self::$status_names,
        ];

        return view('orders.index',$data);

    }

    public function detail(Order $order)
    {
        $order_items = $order->order_items;
        $data = [
            'order'=>$order,
            'order_items'=>$order_items,
            'status_names'=>self::$status_names,
        ];
        return view('orders.detail',$data);
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

        $data=[
            'customers'=>$customers,
            'welfares' =>$welfares,
            'users'=>$users,
            'status_names' => self::$status_names,
            'payment_method_names'=>self::$payment_method_names,
            'products'=>$products,
        ];
        return view('orders.create',$data);



    }
    public function rules(Request $request)
    {
        // general rules
        $rules = [
            'customer_id' => 'required',
            'welfare_id' => 'required',
            'business_concat_person_id'=>'required',
//            'phone_number'=>'required',
//            'email'=>'required',
            'e7line_account'=>'required|not_in:-1',
            'e7line_name'=>'required',
            'user_id'=>'required',
            'status'=>'required',
            'ship_to'=>'required',
            'payment_method'=>'required',
            'product_id.*'=>'required',
            'product_detail_id.*'=>'required',
            'quantity.*'=>'required',
            'price.*'=>'required',
        ];

        // conditional rules
        if($request->input('customer_id') == -1){
            $rules['other_customer_name'] = 'required';
        }
        if($request->input('business_concat_person_id')==-1){
            $rules['other_concat_person_name']= 'required';
        }
        return $rules;
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
//        dd($request);
        $this->validate($request,$this->rules($request));

        $data = $request->all();
        $product_info = [
            'product_id'=>$data['product_id'],
            'product_detail_id'=>$data['product_detail_id'],
            'quantity' =>$data['quantity'],
            'price' => $data['price'],
        ];
        unset($data['_token']);
        if($data['customer_id']==-1){
            unset($data['customer_id']);
        }
        if($data['business_concat_person_id']==-1){
            unset($data['business_concat_person_id']);
        }

        unset($data['redirect_to']);
        unset($data['product_id']);
        unset($data['product_detail_id']);
        unset($data['quantity']);
        unset($data['price']);

        $order = Order::create($data);

//  create order item

        $amount = 0;
        for($i=0;$i<count($product_info['product_id']);$i++){
            $product_relation = ProductRelation::where('product_id','=',$product_info['product_id'][$i])
                ->where('product_detail_id','=',$product_info['product_detail_id'][$i])->first();
            $order_item  = OrderItem::create([
                'order_id'=>$order->id,
                'product_relation_id'=>$product_relation->id,
                'quantity'=>$product_info['quantity'][$i],
                'price'=>$product_info['price'][$i],
                'create_date'=>now(),
                'update_date'=>now(),
            ]);

            $amount += $product_info['price'][$i] * $product_info['quantity'][$i];
        }

        $order->amount = $amount;
        $order->create_date = now();
        $order->update_date = now();
        $order->update();

        return redirect()->route('orders.index');



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
    public function get_customer_concat_persons(Request $request)
    {
        $customer_id = $request['customer_select_id'];
//        $welfare_status = WelfareStatus::where('customer_id','=',$customer_id)->get()->pluck('welfare_name','id');
        $concat_persons = BusinessConcatPerson::where('customer_id', '=', $customer_id)->pluck('name','id')->toArray();

        return $concat_persons;
    }

    public function get_product_details(Request $request)
    {
        $product_id = $request['product_id'];
        $product = Product::find($product_id);
        $product_relations = $product->product_relations;

        $arr = [];
        foreach ($product_relations as $product_relation){
            $product_detail = $product_relation->product_detail;
            $arr[$product_detail->id] = array($product_detail->name,$product_detail->price);
        }
        return $arr;
    }

    public function get_product_details_price(Request $request)
    {
        $product_detail = ProductDetail::find($request['product_detail_id']);
        return $product_detail->price;
    }


}
