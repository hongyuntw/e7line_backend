<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductDetail;
use App\ProductRelation;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $product_details = ProductDetail::all();
        $data = [
            'products'=>$products,
            'product_details'=>$product_details,
        ];
        return view('products.create',$data);
    }
    public function rules(Request $request)
    {
        // general rules
        $rules = [
            'product_id' => 'required',
            'product_detail_id.*' =>'required',
        ];

        // conditional rules
        if ($request->input('product_id') == -1) {
            $rules['product_name'] = 'required|unique:products,name';
        }

//        dump($request->input('product_detail_id'));

        for($i = 0; $i< count($request->input('product_detail_id')); $i++){
            if($request->input('product_detail_id')[$i]==-1){
                $str = 'product_detail.' . $i;
                $rules[$str] = 'required';
            }
        }
//        dd($rules);
        return $rules;
    }


    public function validate_product_form(Request $request){
//        $msg = '';
//        dd($this->validate($request, $this->rules($request)));
        return $this->validate($request, $this->rules($request));
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
//        dd($this->validate($request, $this->rules($request)));

        if($request->input('product_id') == -1){
            $product = Product::create([
                'name' => $request->input('product_name'),
                'create_date'=> now(),
                'update_date'=>now(),
            ]);
        }
        else{
            $product = Product::find($request->input('product_id'));
        }

        $product_detail_names = $request->input('product_detail');
        $product_detail_ids = $request->input('product_detail_id');
        $product_ISBN = $request->input('ISBN');
        $product_price = $request->input('price');

        for($i = 0; $i<count($product_detail_names);$i++){
            if($product_detail_ids[$i] == -1){
//                find if already have the set in product
                $product_detail = ProductDetail::where('name','=',$product_detail_names[$i])->first();
                if(!$product_detail){
//                    create new product detail
                    $product_detail = ProductDetail::create([
                        'name' => $product_detail_names[$i],
                        'create_date'=> now(),
                        'update_date'=>now(),
                    ]);
                }
            }
            else{
                $product_detail = ProductDetail::find($product_detail_ids[$i]);
            }


            $product_relation = ProductRelation::where('product_id','=',$product->id)
                ->where('product_detail_id','=',$product_detail->id)->first();

            if(!$product_relation){
                $product_relation = ProductRelation::create([
                    'product_id' => $product->id,
                    'product_detail_id'=>$product_detail->id,
                    'create_date' => now(),
                ]);
            }

            $product_relation->ISBN = $product_ISBN[$i];
            $product_relation->price = $product_price[$i];
            $product_relation->update();

//            setting price and ISBN
        }

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
