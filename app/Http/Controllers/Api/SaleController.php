<?php

namespace App\Http\Controllers\Api;

use App\Sale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $member = auth('api')->user();
        $sales = $member->sales;
        return response()->json($sales);
    }

    public function salesitems(Sale $sale)
    {
        $salesitems= $sale->salesitems;
        return response()->json($salesitems);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $this->validate(
            $request,
            [
                'member_id'=>'required',
                'order_name' => 'required|string',
                'order_phone' => 'required',
                'order_address' => 'required|string',
            ]
        );

        Sale::create([
            'member_id' => $request->input('member_id'),
            'order_name' => $request->input('order_name'),
            'order_phone' => Hash::make($request->input('order_phone')),
            'order_address' => $request->input('order_address'),
            'order_date' => now(),
            'order_note'=>$request->input('order_note'),
        ]);

        return response()->json([
            'success' => true,
        ]);
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
