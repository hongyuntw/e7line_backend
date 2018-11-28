<?php

namespace App\Http\Controllers;

use App\Member;
use App\Sale;
use App\SalesItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sales_today = Sale::whereDate('created_at', '=', date('Y-m-d'))->count();
        $sale_month = Sale::whereYear('created_at', '=', date('Y'))->get();
        $income_month = 0;
        foreach ($sale_month as $sale)
        {
            $items = $sale->salesitems;
            foreach($items as $item)
            {
                $income_month += $item->quantity * $item->sale_price;
            }
        }
        $members_month = Member::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', date('m'))->count();
        $members_total = Member::all()->count();
        $data = [
            'sales_today' => $sales_today,
            'income_month' => $income_month,
            'members_month' => $members_month,
            'members_total' => $members_total
        ];
        return view('dashboard.index', $data);
    }
}
