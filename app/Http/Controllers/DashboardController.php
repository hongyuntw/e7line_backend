<?php

namespace App\Http\Controllers;

use App\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sales = Sale::whereDate('created_at', '=', date('Y-m-d'))->count();
        $data = [
            'sales' => $sales
        ];
        return view('dashboard.index', $data);
    }
}
