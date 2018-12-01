<?php

namespace App\Http\Controllers;

use App\Member;
use App\Sale;
use App\Type;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        //今日訂單
        $sales_today = Sale::whereDate('order_date', '=', date('Y-m-d'))->count();

        //本月收入
        $sale_month = Sale::whereMonth('order_date', '=', date('m'))->get();
        $income_month = [];
//        foreach ($sale_month as $sale)
//        {
//            $items = $sale->salesitems;
//            foreach($items as $item)
//            {
//                $income_month += $item->quantity * $item->sale_price;
//            }
//        }

        //本月新會員
        $members_month = Member::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->count();

        //會員數量
        $members_total = Member::all()->count();

        //營收表(半年)
        for( $i=5 ; $i>=0 ; --$i)
        {
            $current_yd = Carbon::now()->subMonthsNoOverflow($i);
            $sale_month = Sale::whereRaw('YEAR(order_date) = '.$current_yd->year)->whereRaw('MONTH(order_date) = '.$current_yd->month)->get();

            $income = 0;
            foreach ($sale_month as $sale)
            {
                $items = $sale->salesitems;
                foreach($items as $item)
                {
                    $income += $item->quantity * $item->sale_price;
                }
            }
            $income_month[$current_yd->year.'-'.$current_yd->month] = $income;
        }

        //取得四大項目的銷售數量
        $type = Type::select('name')->get();


        $data = [
            'sales_today' => $sales_today,
            'income_month' => $income_month,
            'members_month' => $members_month,
            'members_total' => $members_total,
            'type' => $type
        ];

        return view('dashboard.index', $data);
    }
}
