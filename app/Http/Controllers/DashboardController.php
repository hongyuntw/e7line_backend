<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
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
//        $customers = Customer::orderBy('created_at')->paginate(15);

        $customers = Customer::where('status','=',3)->paginate(15);

        $data = [
            'customers' => $customers,
        ];



        return view('dashboard.index' , $data);
    }
}
