<?php

namespace App\Http\Controllers;

use App\Sale;
use App\SalesItem;
use Illuminate\Http\Request;

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
        $sales = Sale::orderBy('created_at')->get();

        $data = [
            'sales' => $sales,
        ];
        return view('sales.index', $data);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        //

        $salesitems = $sale->salesitems;
        $data = [
            'sale' => $sale,
            'salesitems' => $salesitems,
//            'salesitems'=> $sale->salesitem(),

        ];

        return view('sales.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Sale $sale
     * @param SalesItem $salesitems
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
//        dd($request->all());
        //


        $this->validate($request, [
            'order_name' => 'required',
            'order_phone' => 'required',
            'order_address' => 'required',
            'quantity' => 'integer'
        ]);
        $sale->update($request->all());
        return redirect()->route('sales.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        //
        $sale->delete();

        return redirect()->route('sales.index');
    }
}
