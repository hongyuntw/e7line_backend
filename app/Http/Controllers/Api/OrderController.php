<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Member;
use App\Sale;
use App\SalesItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        //Log::info($request->all());

        //$member = Member::where('id',$request->member_id)->get();
        $member = Member::find($request->member_id);
        $mycarts = $member->carts;
        $sale = Sale::create([
            'member_id' => $request->input('member_id'),
            'order_name' => $request->input('order_name'),
            'order_phone' => $request->input('order_phone'),
            'order_address' => $request->input('order_address'),
            'order_note' => $request->input('order_note'),
            'order_date' => now(),
            'shipment' => 0,
        ]);
        $coupon = Coupon::where('code', '=', $request->code)->first();
        foreach ($mycarts as $salesitem) {
            $salesitem = SalesItem::create([
                'sale_id' => $sale->id,
                'product_id' => $salesitem->product_id,
                'quantity' => $salesitem->quantity,
                'sale_price' => 0,
            ]);
            if (!$coupon) {
                $salesitem->sale_price = $salesitem->product->saleprice;
            } else if ($coupon->type == 1) {
                $salesitem->sale_price = $salesitem->product->saleprice * 0.9;
                $coupon->is_used = 1;
                $coupon->update();
            } else if ($coupon->type == 2) {
                $salesitem->sale_price = $salesitem->product->saleprice * 0.8;
                $coupon->is_used = 1;
                $coupon->update();
            } else if ($coupon->type == 3) {
                $salesitem->sale_price = $salesitem->product->saleprice * 0.7;
                $coupon->is_used = 1;
                $coupon->update();
            }
            $salesitem->update();
        }
        foreach ($mycarts as $mycart) {
            Cart::destroy($mycart->id);
        }
        return response()->json([
            'success' => true,
        ]);
    }
}
