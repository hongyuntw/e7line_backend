<?php

namespace App\Http\Controllers;

use App\Charts\SampleChart;
use App\Product;
use App\ProductRelation;
use App\Quote;
use Illuminate\Http\Request;
use ConsoleTVs\Charts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        $quotes = Quote::all();
        $products = Product::all();

        $data = [
            'quotes' => $quotes,
            'products' => $products,
            'product_relations' => $product_relations,
        ];

        return view('quote.index', $data);
    }


    public function getProductQuote(Request $request)
    {
        session_start();
        if(Session::exists('quote_product_id')){
            Session::remove('quote_product_id');
        }
        Session::put('quote_product_id',$request->input('id'));
        session_write_close();
        $product = Product::find($request->input('id'));
        $quotes = $product->quotes;
        $resp = '                                <table class="table table-bordered table-hover" width="100%">
                                    <thead style="background-color: lightgray">
                                    <tr>
                                        <th class="text-center" style="width:15%">級距</th>
                                        <th class="text-center" style="width:20%">原廠%</th>
                                        <th class="text-center" style="width:8%">e7line%</th>
                                        <th class="text-center" style="width:8%">備註</th>
                                        <th class="text-center" style="width:20%">Other</th>
                                    </tr>
                                    </thead>';

        foreach ($quotes as $quote) {
            if ($quote->is_deleted) {
                continue;
            }
            $resp .= '<tr ondblclick="" class="text-center">';
            $resp .= '<td>' . $quote->step . '</td>';
            $resp .= '<td>' . $quote->origin . '</td>';
            $resp .= '<td>' . $quote->e7line . '</td>';
            $resp .= '<td>' . $quote->note . '</td>';
            $resp.= '<td>';
            $resp .= '<a class="btn btn-xs btn-primary" href="'.route('quote.edit',$quote->id).'" >編輯</a>';
            if (Auth::user()->level == 2) {
                $resp .= '<form action="' . route('quote.delete',$quote->id) . '" method="post"
                                                          style="display: inline-block">';
                $resp.= csrf_field();
                $resp .= '<button type="submit" class="btn btn-xs btn-danger"
                                                                onclick="return confirm("確定是否刪除")">刪除
                                                        </button>
                                                    </form>';
            }
            $resp .= '</td></tr>';


        }
        $resp .= '</table>';
        return $resp;


    }


    public function initIndex()
    {
        session_start();
        $product_id = Session::get('quote_product_id');
        if(empty($product_id)){
            session_write_close();  //<---------- Add this to close the session so that reading from the session will contain the new value.
            return  '<table></table>';

        }
        session_write_close();  //<---------- Add this to close the session so that reading from the session will contain the new value.

        $product = Product::find($product_id);
        $quotes = $product->quotes;
        $resp = '                                <table class="table table-bordered table-hover" width="100%">
                                    <thead style="background-color: lightgray">
                                    <tr>
                                        <th class="text-center" style="width:15%">級距</th>
                                        <th class="text-center" style="width:20%">原廠%</th>
                                        <th class="text-center" style="width:8%">e7line%</th>
                                        <th class="text-center" style="width:8%">備註</th>
                                        <th class="text-center" style="width:20%">Other</th>
                                    </tr>
                                    </thead>';

        foreach ($quotes as $quote) {
            if ($quote->is_deleted) {
                continue;
            }
            $resp .= '<tr ondblclick="" class="text-center">';
            $resp .= '<td>' . $quote->step . '</td>';
            $resp .= '<td>' . $quote->origin . '</td>';
            $resp .= '<td>' . $quote->e7line . '</td>';
            $resp .= '<td>' . $quote->note . '</td>';
            $resp.= '<td>';
            $resp .= '<a class="btn btn-xs btn-primary" href="'.route('quote.edit',$quote->id).'" >編輯</a>';
            if (Auth::user()->level == 2) {
                $resp .= '<form action="' . route('quote.delete',$quote->id) . '" method="post"
                                                          style="display: inline-block">';
                $resp.= csrf_field();
                $resp .= '<button type="submit" class="btn btn-xs btn-danger"
                                                                onclick="return confirm("確定是否刪除")">刪除
                                                        </button>
                                                    </form>';
            }
            $resp .= '</td></tr>';


        }
        $resp .= '</table>';
        return $resp;

    }





    public function getChartData(Request $request)
    {
        if ($request->has('product_relation_id')) {
            $product_relation_id = $request->input('product_relation_id');
            $product_relation = ProductRelation::find($product_relation_id);
            $title = $product_relation->product->name . ' ' . $product_relation->product_detail->name;

            $order_items = $product_relation->order_items()->orderBy('create_date')->get();

//            dd($order_items);
            $data = [];
            foreach ($order_items as $order_item) {
                if (array_key_exists($order_item->price, $data)) {
                    $data[$order_item->price] += $order_item->quantity;
                } else {
                    $data[$order_item->price] = $order_item->quantity;
                }
            }
            ksort($data, 1);

            return [
                'quantity' => array_values($data),
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
        $products = Product::all();
        $data = [
            'products' => $products
        ];

        return view('quote.create', $data);
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
            'product_id' => 'required|integer|min:1',
            'step' => 'required',
            'origin' => 'required|numeric|min:0|max:100',
            'e7line' => 'required|numeric|min:0|max:100',
        ]);
        $data = $request->all();
//        dd($data);
        unset($data['token']);
        unset($data['redirect_to']);

        $quote = Quote::Create($data);
        $quote->create_date = now();
        $quote->update();
        session_start();
        if(Session::exists('quote_product_id')){
            Session::remove('quote_product_id');
        }
        Session::put('quote_product_id',$request->input('product_id'));
        session_write_close();
        return redirect()->route('quote.index');

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
    public function edit(Request $request, Quote $quote)
    {
        //
        $data = [
            'quote' =>$quote,
            'products'=> Product::all(),
        ];
        return view('quote.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {
        //
        $this->validate($request, [
            'product_id' => 'required|integer|min:1',
            'step' => 'required',
            'origin' => 'required|numeric|min:0|max:100',
            'e7line' => 'required|numeric|min:0|max:100',
        ]);
        $data = $request->all();
//        dd($data);
        unset($data['token']);
        unset($data['redirect_to']);
        $quote->update($data);
        return redirect()->route('quote.index');

    }

    public function delete(Quote $quote)
    {
        $quote->is_deleted = 1;
        $quote->update();
        return redirect()->route('quote.index');

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
}
