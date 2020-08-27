<?php

namespace App\Http\Controllers;

use App\ConcatRecord;
use App\Order;
use App\OrderItem;
use App\Product;
use App\ProductRelation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\PHP;

class OrderItemController extends Controller
{

    public static $order_item_status_names = ['尚未受理', '已收單', '已叫貨', '已交貨'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
//        dd($request);
        $status = 0;
        $sortBy_text = ['建單日期', '收件日期'];
        $user_filter = -1;
        $status_filter = -1;
        $sortBy = 'create_date';
        $query = OrderItem::query();
        $search_type = 0;
        $search_info = '';
        $products = Product::all();
        $users = User::all();
        $product_id = -1;
        $product_detail_id = -1;
        $product_relation = null;
        $date_from = null;
        $date_to = null;
        $senao_order_filter = -1;
        $perPage = 15;

        $query->join('orders', 'order_items.order_id', '=', 'orders.id');
        $query->select('order_items.*', 'orders.user_id as user_id', 'order_items.status as status');
//        dd($query->first());

        //   get     sort
        if ($request->has('sortBy')) {
            $sortBy = $request->input('sortBy');
        }


//        user
        if ($request->has('user_filter')) {
            $user_filter = $request->input('user_filter');
        }
        if ($user_filter > 0) {
            $query->where('user_id', '=', $user_filter);
        }

        if($request->has('senao_order_filter')){
            $senao_order_filter  = $request->input('senao_order_filter');
        }
        if($senao_order_filter == 0) {
            $query->whereNull('orders.senao_order_id');
        }
        else if($senao_order_filter == 1){
            $query->whereNotNull('orders.senao_order_id');
            $perPage = 99999;
        }

// status
        if ($request->has('status_filter')) {
            $status_filter = $request->input('status_filter');
        }
        if ((int)$status_filter >= 0) {
            $query->where('order_items.status', '=', (int)$status_filter);
        }
//        product
        if ($request->has('product_id')) {
            $product_id = $request->query('product_id');
        }
        if ($request->has('product_detail_id')) {
            $product_detail_id = $request->query('product_detail_id');
        }
//      check if user select product ornot
//        dump($product_detail_id);
//        dd($product_id);
        if ($product_detail_id != -1 || $product_id != -1) {
            $query->join('product_relations', 'order_items.product_relation_id', '=', 'product_relations.id');
            if ($product_id != -1 && $product_detail_id != -1) {
                $query->where('product_id', '=', $product_id)
                    ->where('product_detail_id', '=', $product_detail_id);
            } else if ($product_id != -1 && $product_detail_id == -1) {
                $query->where('product_id', '=', $product_id);
            }
//            else{
//                $query->where('product_detail_id', '=', $product_detail_id);
//            }

        }
////        get the selected product
//        if($product_relation != null){
//            $query->where('product_relation_id','=',$product_relation->id);
//        }

//        date filter
        if ($request->has('date_from')) {
            $date_from = $request->input('date_from');
        }
        if ($request->has('date_to')) {
            $date_to = $request->input('date_to');
        }
        if ($date_from != null && $date_to != null) {
            $date_from_addtime = $date_from . " 00:00:00";
            $date_to_addtime = $date_to . " 23:59:59";
            $query->whereBetween('orders.' . $sortBy, [$date_from_addtime, $date_to_addtime]);
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
                    $query->join('customers', 'customers.id', '=', 'orders.customer_id');
                    $query->where(function ($query) use ($search_info) {
                        $query->where('customers.name', 'like', "%{$search_info}%")
                            ->orWhere('orders.other_customer_name', 'like', "%{$search_info}%");
                        return $query;

                    });
                    break;
                case 3:
                    $query->join('business_concat_persons', 'business_concat_persons.id', '=', 'orders.business_concat_person_id');
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


        $query->where('is_deleted', '=', 0);
        $query->orderBy($sortBy, 'DESC');


        $query_for_all = $query;
//        here to create msg for 採購
        $order_items_all = $query_for_all->get();

//        this is record every product and every item total quantity
        $qt_arr = [];
        foreach ($order_items_all as $item) {
            if (array_key_exists($item->product_relation->product->name, $qt_arr)) {
                $detail_and_spec = $item->product_relation->product_detail->name;
                if($item->spec_name){
                    $detail_and_spec .= '(' . $item->spec_name .')';
                }
                if (array_key_exists($detail_and_spec, $qt_arr[$item->product_relation->product->name])) {
                    $qt_arr[$item->product_relation->product->name][$detail_and_spec] += $item->quantity;
                }
                else {
                    $qt_arr[$item->product_relation->product->name][$detail_and_spec] = $item->quantity;
                }
            }
            else {
                $detail_and_spec = $item->product_relation->product_detail->name;
                if($item->spec_name){
                    $detail_and_spec .= '(' . $item->spec_name .')';
                }
                $qt_arr[$item->product_relation->product->name][$detail_and_spec] = $item->quantity;
            }
        }
        $msg = '<a style="color:black;cursor: pointer" onclick="clearCountResult()">清除查詢結果</a> <br>';
        $msg .= '產品數量統計:<br>';
        foreach ($qt_arr as $key => $value) {
            $msg .= '-----' . $key . '-----<br>';
            foreach ($value as $name => $qty) {
                $msg .= $name . ' 總數量: ' . $qty . '<br>';
            }
        }
        $msg .= '<a style="color:black;cursor: pointer" onclick="clearCountResult()">清除查詢結果</a> <br>';



        $order_items = $query->paginate($perPage);

        $data = [
            'products' => $products,
            'order_items' => $order_items,
            'order_item_status_names' => self::$order_item_status_names,
            'status_filter' => $status_filter,
            'product_id' => $product_id,
            'product_detail_id' => $product_detail_id,
            'user_filter' => $user_filter,
            'users' => $users,
            'sortBy' => $sortBy,
            'sortBy_text' => $sortBy_text,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'msg' => $msg,
            'senao_order_filter' => $senao_order_filter,
        ];

        return view('order_items.index', $data);
    }

//    public function compute_quantity(Request $request)
//    {
//        return $request;
//    }


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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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


    public function change_item_status(Request $request)
    {
        $msg = "";
        if ($request['ids']) {
            $status =  (int)$request['status'];
            foreach ($request['ids'] as $id) {
                $order_item = OrderItem::find($id);
                //當有一筆訂單改為以收單，其他同筆大單下之細項要改為以收單
//                判斷是否是從尚未處裡=>以收單
                if ($order_item->status == 0 && $status == 1) {
                    foreach ($order_item->order->order_items as $order_item) {
//                        若有未處理的
                        if ($order_item->status == 0) {
                            $order_item->status = 1;
                            $order_item->update_date = now();
                            $order_item->update();
                        }
                    }




                }



//                若有從尚未處理變到已叫/交貨時應該新增提醒
                if ($order_item->status == 0 && $status > 1) {
                    $msg .= '提醒: 已將訂單編號' . $order_item->order->no . '狀態從未完成改成已叫(交)貨' . PHP_EOL;
                }

                $order = $order_item->order;

                // 若訂單細項有被更改從 以收單=>未處理
                if($order->status > 0  && $order_item->status > 0 && $status == 0){
                    $order->status = 0;
                    $order->update_date = now();
                    $order->update();
                }
                //  把訂單從未處理=>處理中
                else if ($order->status == 0  &&  $status > 0 ) {
                    $order->status = 1;
                    $order->update_date = now();
                    $order->update();
                }



                $order_item->status = $status;
                $order_item->update_date = now();
                $order_item->update();


                $order_items = $order->order_items;
//                判斷全部訂單是否都是已交貨
                $all_success_flag = true;
                foreach ($order_items as $o_i) {
                    if ($o_i->status != 3) {
                        $all_success_flag = false;
                        break;
                    }
                }
                //              如果此筆大訂單之所有商品都已完成，訂單狀態自動變成完成
                if ($all_success_flag) {
                    $order->status = 2;
                    $order->update_date = now();
                    $order->update();
                }









            }
        }

        $data = [
            'success' => true,
            'msg' => $msg,
        ];
        return $data;

    }
}
