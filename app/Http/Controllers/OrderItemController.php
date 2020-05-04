<?php

namespace App\Http\Controllers;

use App\ConcatRecord;
use App\Order;
use App\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{

    public static $order_item_status_names = ['尚未受理','已叫貨','已出貨','已交貨','已收單'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $order_items = OrderItem::orderBy('create_date','DESC')->paginate(15);
//        $orders = Order::paginate(15);
        $data=[
            'order_items' => $order_items,
            'order_item_status_names'=>self::$order_item_status_names,
        ];

        return view('order_items.index',$data);
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


    public function change_item_status(Request $request)
    {
        if($request['ids']){
            $status = $request['status'];
            foreach($request['ids'] as $id){
                $order_item = OrderItem::find($id);
                $order_item->status = $status;
                $order_item->update_date = now();
                $order_item->update();

            }
        }
        return "success";
    }
}
