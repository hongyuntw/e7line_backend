<?php

namespace App\Http\Controllers;

use App\IsbnRelation;
use App\Order;
use App\Product;
use App\ProductRelation;
use App\SenaoProduct;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Redirect;


class SenaoProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $search_type = 0;
        $search_info = '';
        $query = SenaoProduct::query();
        $products = Product::all();
        $product_id = -1;
        $product_detail_id = -1;

        //        product
        if ($request->has('product_id')) {
            $product_id = $request->query('product_id');
        }
        if ($request->has('product_detail_id')) {
            $product_detail_id = $request->query('product_detail_id');
        }
//      check if user select product or not
        $product_relation_id_array = array();
        if ($product_detail_id != -1 || $product_id != -1) {
            if ($product_id != -1 && $product_detail_id != -1) {
                $id_array = ProductRelation::where('product_id', '=', $product_id)
                    ->where('product_detail_id', '=', $product_detail_id)->pluck('id')->toArray();
                $product_relation_id_array = array_merge($product_relation_id_array, $id_array);
            }
            else if ($product_id != -1 && $product_detail_id == -1) {
                $id_array = ProductRelation::where('product_id', '=', $product_id)->pluck('id')->toArray();
                $product_relation_id_array = array_merge($product_relation_id_array, $id_array);
            }
            $senao_id_array = IsbnRelation::whereIn('product_relation_id',$product_relation_id_array)
                ->pluck('senao_product_id')->toArray();
            $query->whereIn('id',$senao_id_array);
        }

        if ($request->has('search_type')) {
            $search_type = $request->query('search_type');
        }
        if ($search_type > 0) {
            $search_info = $request->query('search_info');
            switch ($search_type) {
                case 1:
                    $query->where('senao_isbn','like', "%{$search_info}%");
                    break;
                case 2:
                    $search_isbn = ProductRelation::where('ISBN','like', "%{$search_info}%")->pluck('id')->toArray();
                    $isbn_relations = IsbnRelation::whereIn('product_relation_id',$search_isbn)->pluck('senao_product_id')->toArray();
                    $query->whereIn('id',$isbn_relations);
                    break;

                default:
                    break;
            }
        }

        $query->orderBy('create_date','DESC');
        $senao_products = $query->paginate(15);

        $data = [
            'senao_products' => $senao_products,
            'search_type' =>$search_type,
            'search_info' =>$search_info,
            'products' => $products,
            'product_id' => $product_id,
            'product_detail_id'=>$product_detail_id,

        ];
        return view('senao_products.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $product_array = self::getProductArray();

        $data = [
            'product_array' => $product_array,
        ];


        return view('senao_products.create',$data);

    }

    public function getProductArray()
    {
        $product_relations = ProductRelation::all();
        $product_array = array();
        foreach($product_relations as $product_relation){
            if(!is_null($product_relation->ISBN) and $product_relation->ISBN != ''){
                $product_array[$product_relation->ISBN] = $product_relation->product->name . $product_relation->product_detail->name;
            }
        }
        return $product_array;
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
        $this->validate($request, [
            'senao_isbn' => 'required',
            'price.*' => 'required',
            'ISBN' => 'required',
            'ISBN.*' => 'required|not_in:-1'
        ]);

        $msgs = [];
        $senao_isbn = $request->input('senao_isbn');
        $price_array = $request->input('price');
        $isbn_array = $request->input('ISBN');


        foreach (array_combine($price_array, $isbn_array) as $price => $isbn) {
//            check senao isbn exist or not
            $senao_product =  SenaoProduct::where('senao_isbn','=',$senao_isbn)->first();
            if(is_null($senao_product)){
                $senao_product = SenaoProduct::create([
                    'senao_isbn' => $senao_isbn,
                ]);
            }
            $product_relation = ProductRelation::where('ISBN','=',$isbn)->first();
            $isbn_relation = IsbnRelation::where('senao_product_id','=',$senao_product->id)
                ->where('product_relation_id','=',$product_relation->id)->first();
            if(is_null($isbn_relation)){
                $isbn_relation = IsbnRelation::create([
                    'price' => $price,
                    'senao_product_id' => $senao_product->id,
                    'product_relation_id' => $product_relation->id,
                ]);
                $msg = '新增神腦料號: '. $senao_isbn . ' 商品ISBN: ' . $isbn . ' 成功';
                array_push($msgs,$msg);
            }
            else{
                $msg = '神腦料號: '. $senao_isbn . ' 商品ISBN: ' . $isbn . ' 已經存在資料庫';
                array_push($msgs,$msg);
            }
        }

        Session::flash('msgs',$msgs);
        return redirect()->back();
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
    public function edit(IsbnRelation $isbn_relation,Request $request)
    {
        //
//        dd($request);
        $product_name = $isbn_relation->product_relation->product->name .  ' ' . $isbn_relation->product_relation->product_detail->name;

        $source_html = $request->input('source_html');
        $data = [
            'isbn_relation' => $isbn_relation,
            'product_name' => $product_name,
            'source_html' => $source_html,
        ];
        return view('senao_products.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IsbnRelation $isbn_relation)
    {
        //
        $this->validate($request, [
            'price' => 'required',
        ]);
        $isbn_relation->price = $request->input('price');
        $isbn_relation->update();
        $source_html = $request->input('source_html');

        return Redirect::to($source_html);

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
