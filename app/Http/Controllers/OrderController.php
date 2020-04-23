<?php

namespace App\Http\Controllers;

use App\BusinessConcatPerson;
use App\Customer;
use App\Order;
use App\User;
use App\Welfare;
use App\WelfareStatus;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $orders = Order::paginate(15);
        $data=[
            'orders'=>$orders
        ];

        return view('orders.index',$data);

    }

    public function detail(Order $order)
    {
        $order_items = $order->order_items;
        $data = [
            'order'=>$order,
            'order_items'=>$order_items,
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

        $data=[
            'customers'=>$customers,
            'welfares' =>$welfares,
            'users'=>$users,

        ];
        return view('orders.create',$data);



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
    public function get_customer_concat_persons(Request $request)
    {
        $customer_id = $request['customer_select_id'];
//        $welfare_status = WelfareStatus::where('customer_id','=',$customer_id)->get()->pluck('welfare_name','id');
        $concat_persons = BusinessConcatPerson::where('customer_id', '=', $customer_id)->pluck('name','id')->toArray();

//        $arr = [];
//        foreach ($concat_persons as $wt) {
//            if (count($wt->welfare_types) <= 0) {
//                $arr[$wt->id] = $wt->welfare_name;
//            }
//        }
//        return $welfare_status;
        return $concat_persons;
    }


}
