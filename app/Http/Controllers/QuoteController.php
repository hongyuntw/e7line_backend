<?php

namespace App\Http\Controllers;

use App\Charts\SampleChart;
use App\ProductRelation;
use Illuminate\Http\Request;
use ConsoleTVs\Charts;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $product_relations = ProductRelation::all();
//        $chart = new SampleChart();
//        $chart->labels(['1','2','3']);  // X轴数据
//        $chart->title('報價參考'); //标题
//        $chart->dataset('無', 'line', [0]);
//        $chart->load(route('qoute.getChart'));
        $data = [
            'product_relations' => $product_relations,
//            'chart' =>$chart,
        ];
//        dump($chart->container());
//        dd($chart->script());

        return view('quote.index',$data);
    }

    public function getChartData(Request $request)
    {
        if($request->has('product_relation_id')){
            $product_relation_id = $request->input('product_relation_id');
            $product_relation = ProductRelation::find($product_relation_id);
            $title = $product_relation->product->name .' '. $product_relation->product_detail->name;

            $order_items = $product_relation->order_items()->orderBy('create_date')->get();

//            dd($order_items);
            $data  = [];
            foreach ($order_items as $order_item){
                if(array_key_exists($order_item->price, $data)){
                    $data[$order_item->price] += $order_item->quantity;
                }
                else{
                    $data[$order_item->price] = $order_item->quantity;
                }
            }
            ksort($data,1);

            return [
                'quantity'=> array_values($data),
                'title' => $title,
                'prices' => array_keys($data),
            ];

        }


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
