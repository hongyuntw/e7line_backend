<?php

namespace App\Http\Controllers;

use App\Category;
use App\Member;
use App\Sale;
use App\Type;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        //今日訂單
        $sales_today = Sale::whereDate('order_date', '=', date('Y-m-d'))->count();

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

        //本月收入
        //取得四大項目的銷售數量
        $type = DB::select(DB::raw('SELECT types.name AS type, ROUND(SUM(sales_items.quantity * sales_items.sale_price)/(SELECT SUM(sales_items.quantity * sales_items.sale_price) FROM sales_items) * 100,1) AS proportion '.
                                'FROM sales_items '.
                                'INNER JOIN products ON sales_items.product_id = products.id '.
                                'INNER JOIN categories ON products.category_id = categories.id '.
                                'INNER JOIN types ON categories.type_id = types.id '.
                                'GROUP BY types.name;'));
//        dd($result);

        //輸出資料
        $data =
        [
            'sales_today' => $sales_today,
            'income_month' => $income_month,
            'members_month' => $members_month,
            'members_total' => $members_total,
            'type' => $type
        ];



        return view('dashboard.index', $data);
    }
}
