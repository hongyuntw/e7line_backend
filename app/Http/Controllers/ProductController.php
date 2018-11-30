<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Type;
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
        $products = Product::orderBy('created_at', 'DESC')->paginate(15);

        $data = [
            'products' => $products,
        ];

        return view('products.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        $categories = Category::all();
        $data = [
            'types' => $types,
            'categories' => $categories,
        ];

        return view('products.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'saleprice' => 'required|integer',
            'listprice' => 'required|integer',
            'unit' => 'required',
            'description' => 'required',
            'image' => 'required|image',
        ]);
//        $file = $request->file('upload');
        $file = $request->file('image');
        $product = Product::create($request->all());
        $unique_name = $product->id.'.'.$file->extension();
        $product->imagename = $unique_name;
        $product->update();
        $request->file('image')->move(public_path().'/storage',$unique_name);
       // $path = $request->file->storeAs('路路徑', '');

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    public function showup()
    {
        $products = Product::where('isSelling',1)->orderBy('created_at', 'DESC')->paginate(15);

        $data = [
            'products' => $products,
        ];
        return view('products.up', $data);
    }
    public function showremove()
    {
        $products = Product::where('isSelling',0)->orderBy('created_at', 'DESC')->paginate(15);

        $data = [
            'products' => $products,
        ];
        return view('products.remove', $data);;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $types = Type::all();
        $categories = Category::all();
        $data = [
            'product' => $product,
            'types' => $types,
            'categories' =>$categories,
        ];

        return view('products.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'required',
            'saleprice' => 'required|integer',
            'unit' => 'required',
            'description' => 'required',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index');
    }

    public function upup(Product $product)
    {
        $product->isSelling = 1;
        $product->update();
        return redirect()->back();
    }
    public function remove(Product $product)
    {
        $product->isSelling = 0;
        $product->update();
        return redirect()->back();
    }
}
