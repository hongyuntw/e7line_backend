<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $carts = Cart::all();

//        return ProductResource::collection($products);
//        return response()->json($carts);
        return CartResource::collection($carts);
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
        $carts = Cart::where('member_id',$id)->get();
//        return new CartResource($cart);
        return CartResource::collection($carts);
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

    public function addincart()
    {
        
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

    public function sub(Request $request)
    {
        $cart = Cart::find($request->id);
        $cart->quantity +=1;
        $cart->update();
        return response()->json([
            'success' => true,
        ]);
    }
    public function add(Request $request)
    {
        $cart = Cart::find($request->id);
        $cart->quantity +=1;
        $cart->update();
        return response()->json([
            'success' => true,
        ]);
    }
    public function delete(Request $request)
    {
        $cart = Cart::find($request->id);

        $cart->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
