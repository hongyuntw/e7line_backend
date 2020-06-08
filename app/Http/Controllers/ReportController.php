<?php

namespace App\Http\Controllers;

use App\ConcatRecord;
use App\Customer;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\AssignOp\Concat;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        if (Auth::user()->level == 2) {
//            filter value
            $date_from = now()->subDays(7)->format('Y-m-d');
            $date_to = now()->format('Y-m-d');


            $data = [
                'date_to' => $date_to,
                'date_from' => $date_from,

            ];

            return view('report.indexAdmin', $data);
        } else {
            $date_from_interval1 = now()->subDays(14)->format('Y-m-d');
            $date_to_interval1 = now()->subDays(7)->format('Y-m-d');

            $date_from_interval2 = now()->subDays(7)->format('Y-m-d');
            $date_to_interval2 = now()->format('Y-m-d');


            $data = [
                'date_to_interval1' => $date_to_interval1,
                'date_from_interval1' => $date_from_interval1,
                'date_to_interval2' => $date_to_interval2,
                'date_from_interval2' => $date_from_interval2,
            ];

            return view('report.indexUser', $data);

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTotalAmountChart(Request $request)
    {
//        時間內建立的訂單，且為處理中
        $date_to = $request->input('date_to');
        $date_from = $request->input('date_from');
        if ($date_from != null && $date_to != null) {
            $date_from_addtime = $date_from . " 00:00:00";
            $date_to_addtime = $date_to . " 23:59:59";

            $users = User::all()->where('level', '=', '0')->where('id', '>', 1)
                ->where('is_left', '=', '0');
            $res = [];
            foreach ($users as $user) {
                $total_amount = Order::all()->whereBetween('create_date', [$date_from_addtime, $date_to_addtime])
                    ->where('user_id', '=', $user->id)->where('status', '=', 1)->sum('amount');
                $res[$user->name] = $total_amount;

            }
            return $res;
        }
        return;
    }

    public function createTotalAmountChart_user(Request $request)
    {
//        時間內建立的訂單，且為處理中
        $date_to_interval1 = $request->input('date_to_interval1');
        $date_from_interval1 = $request->input('date_from_interval1');

        $date_to_interval2 = $request->input('date_to_interval2');
        $date_from_interval2 = $request->input('date_from_interval2');

        if ($date_from_interval1 != null && $date_to_interval1 != null && $date_from_interval2 != null && $date_to_interval2 != null ) {
            $date_from_interval1_addtime = $date_from_interval1 . " 00:00:00";
            $date_to_interval1_addtime = $date_to_interval1 . " 23:59:59";

            $res = [];
            $total_amount = Order::all()->whereBetween('create_date', [$date_from_interval1_addtime, $date_to_interval1_addtime])
                ->where('user_id', '=', Auth::user()->id)->where('status', '=', 1)->sum('amount');
            $res["interval1"] = $total_amount;

            $date_from_interval2_addtime = $date_from_interval2 . " 00:00:00";
            $date_to_interval2_addtime = $date_to_interval2 . " 23:59:59";

            $total_amount = Order::all()->whereBetween('create_date', [$date_from_interval2_addtime, $date_to_interval2_addtime])
                ->where('user_id', '=', Auth::user()->id)->where('status', '=', 1)->sum('amount');
            $res["interval2"] = $total_amount;
            return $res;
        }
        return;
    }

    public function createActiveCustomerChart(Request $request)
    {
//        時間內被設定為自己的客戶
        $date_to = $request->input('date_to');
        $date_from = $request->input('date_from');
        if ($date_from != null && $date_to != null) {
            $date_from_addtime = $date_from . " 00:00:00";
            $date_to_addtime = $date_to . " 23:59:59";

            $users = User::all()->where('level', '=', '0')->where('id', '>', 1)
                ->where('is_left', '=', '0');
            $res = [];
            foreach ($users as $user) {
                $customers = Customer::where('user_id', '=', $user->id)->where('already_set_sales', '=', 1)
                    ->whereBetween('set_sales_date', [$date_from_addtime, $date_to_addtime])->get();
                $res[$user->name] = count($customers);
            }
            return $res;
        }
        return;
    }

    public function createActiveCustomerChart_user(Request $request)
    {
//        時間內建立的訂單，且為處理中
        $date_to_interval1 = $request->input('date_to_interval1');
        $date_from_interval1 = $request->input('date_from_interval1');

        $date_to_interval2 = $request->input('date_to_interval2');
        $date_from_interval2 = $request->input('date_from_interval2');

        if ($date_from_interval1 != null && $date_to_interval1 != null && $date_from_interval2 != null && $date_to_interval2 != null ) {
            $date_from_interval1_addtime = $date_from_interval1 . " 00:00:00";
            $date_to_interval1_addtime = $date_to_interval1 . " 23:59:59";

            $res = [];
            $customers = Customer::where('user_id', '=', Auth::user()->id)->where('already_set_sales', '=', 1)
                ->whereBetween('set_sales_date', [$date_from_interval1_addtime, $date_to_interval1_addtime])->get();

            $res["interval1"] = count($customers);

            $date_from_interval2_addtime = $date_from_interval2 . " 00:00:00";
            $date_to_interval2_addtime = $date_to_interval2 . " 23:59:59";

            $customers = Customer::where('user_id', '=', Auth::user()->id)->where('already_set_sales', '=', 1)
                ->whereBetween('set_sales_date', [$date_from_interval2_addtime, $date_to_interval2_addtime])->get();
            $res["interval2"] = count($customers);
            return $res;
        }
        return;
    }


    public function createRecordCountChart(Request $request)
    {
//        時間內的聯繫不同客戶次數
        $date_to = $request->input('date_to');
        $date_from = $request->input('date_from');
        if ($date_from != null && $date_to != null) {
            $date_from_addtime = $date_from . " 00:00:00";
            $date_to_addtime = $date_to . " 23:59:59";

            $users = User::all()->where('level', '=', '0')->where('id', '>', 1)
                ->where('is_left', '=', '0');
            $res = [];
            foreach ($users as $user) {
                $concat_records = ConcatRecord::all()->whereBetween('create_date', [$date_from_addtime, $date_to_addtime])
                    ->where('user_id', '=', $user->id)->where('is_deleted','=',0)->groupBy('customer_id');
                if ($concat_records) {
                    $res[$user->name] = count($concat_records);
                } else {
                    $res[$user->name] = 0;
                }
            }
            return $res;
        }
        return;
    }

    public function createRecordCountChart_user(Request $request)
    {
//        時間內建立的訂單，且為處理中
        $date_to_interval1 = $request->input('date_to_interval1');
        $date_from_interval1 = $request->input('date_from_interval1');

        $date_to_interval2 = $request->input('date_to_interval2');
        $date_from_interval2 = $request->input('date_from_interval2');

        if ($date_from_interval1 != null && $date_to_interval1 != null && $date_from_interval2 != null && $date_to_interval2 != null ) {
            $date_from_interval1_addtime = $date_from_interval1 . " 00:00:00";
            $date_to_interval1_addtime = $date_to_interval1 . " 23:59:59";

            $res = [];
            $concat_records = ConcatRecord::all()->whereBetween('create_date', [$date_from_interval1_addtime, $date_to_interval1_addtime])
                ->where('user_id', '=', Auth::user()->id)->where('is_deleted','=',0)->groupBy('customer_id');
            if ($concat_records) {
                $res["interval1"] = count($concat_records);
            }
            else {
                $res["interval1"] = 0;
            }

            $date_from_interval2_addtime = $date_from_interval2 . " 00:00:00";
            $date_to_interval2_addtime = $date_to_interval2 . " 23:59:59";

            $concat_records = ConcatRecord::all()->whereBetween('create_date', [$date_from_interval2_addtime, $date_to_interval2_addtime])
                ->where('user_id', '=', Auth::user()->id)->where('is_deleted','=',0)->groupBy('customer_id');
            if ($concat_records) {
                $res["interval2"] = count($concat_records);
            }
            else {
                $res["interval2"] = 0;
            }
            return $res;
        }
        return;
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
