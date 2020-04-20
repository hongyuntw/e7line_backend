<?php

namespace App\Http\Controllers;

use App\Customer;
use App\WelfareTypeName;
use Config;
use App\WelfareStatus;
use App\WelfareType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelfareStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static $status_names = ['Block', 'Pending', 'Success', 'Tracking'];

    public function index(Request $request)
    {

//        dump($request);
        $sortBy = 'customer_id';
        $sortBy_text = ['客戶名稱', '目的', '交易狀況', '預算', '更新日期'];
        $status_filter = -1;


//        0 means show all customers
//        1 means show my customers
        $user_filter = 0;

//        select more than one welfare types welfare
        $query_all = WelfareStatus::query();
        $welfare_statuses = $query_all->get();
        $query = WelfareStatus::query();
        $query->where(function ($query) use ($welfare_statuses) {
            foreach ($welfare_statuses as $key => $value) {
                if (count($value->welfare_types) <= 0) {
                    unset($welfare_statuses[$key]);
                } else {
                    $query->orWhere('welfare_status.id', '=', $value->id);
                }
            }
            return $query;
        });

        $search_type = 0;
        $search_info = '';


        if ($request->has('user_filter')) {
            $user_filter = $request->query('user_filter');
        }
        if ($user_filter > 0) {
            switch ($user_filter) {
                case 1:
                    $query->join('customers', 'welfare_status.customer_id', '=', 'customers.id');
                    $query->where('customers.user_id', '=', Auth::user()->id);
                    break;
                case 2:
                    $query->join('customers', 'welfare_status.customer_id', '=', 'customers.id');
                    $query->where('customers.user_id', '=', 1);
                    break;
                default:
                    break;
            }
        }

        if ($request->has('status_filter')) {
            $status_filter = $request->query('status_filter');
            $query->where(function ($query) use ($status_filter) {
                foreach ($status_filter as $s) {
                    if ($s >= 0) {
                        $query->orWhere('track_status', '=', $s);
                    }
                }
                return $query;
            });
        }


        if ($request->has('search_type')) {
            $search_type = $request->query('search_type');
        }
        if ($search_type > 0) {
            $search_info = $request->query('search_info');
            switch ($search_type) {
                case 1:
                    if ($search_info != '') {
                        $joins = $query->getQuery()->joins;
                        if ($joins == null) {
                            $query->join('customers', 'welfare_status.customer_id', '=', 'customers.id');

                        }
                        $query->where('customers.name', 'like', "%{$search_info}%");
                    }
                    break;
                case 2:
                    if ($search_info != '') {
                        $query->where('welfare_name', 'like', "%{$search_info}%");
                    }
                    break;
                default:
                    break;
            }
        }

        if ($request->has('sortBy')) {
            $sortBy = $request->query('sortBy');
            foreach ($sortBy as $q) {
                $query->orderBy($q,'DESC');
            }
        } else {
            $query->orderBy($sortBy, 'DESC');
        }




        $welfare_statuses = $query->paginate(15);

        $data = [
            'status_names' => self::$status_names,
            'welfare_statuses' => $welfare_statuses,
            'sortBy' => $sortBy,
            'user_filter' => $user_filter,
            'sortBy_text' => $sortBy_text,
            'status_filter' => $status_filter,
        ];
        return view('welfare.index', $data);
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

    public function store_welfare_type(Request $request)
    {

//        dd($request);
        $this->validate($request, [
            'to_be_update.*' => 'required',
        ], [
            'to_be_update.*.required' => 'edit field should not be null'
        ]);

//        edit

        if ($request->has('to_be_update')) {
            foreach ($request->input('to_be_update') as $id => $name) {
                $welfare_type_name = WelfareTypeName::find($id);
                $welfare_type_name->name = $name;
                $welfare_type_name->update();
            }
        }


//        先刪除
//        only admin can delete but may have some problem
        if (Auth::user()->level == 2) {
            if ($request->has('to_be_delete')) {
                foreach ($request->input('to_be_delete') as $id) {
                    $welfare_type_name = WelfareTypeName::find($id);
                    $welfare_type_name->is_deleted = 1;
                    $welfare_type_name->update();

                }

            }

        }
//        再編輯


        if ($request->has('add_welfare_type_names')) {
            foreach ($request->input('add_welfare_type_names') as $name) {
                if ($name) {
                    WelfareTypeName::create([
                        'name' => $name,
                    ]);

                }
            }
        }
        return redirect()->route('welfare_status.index');
    }

    public function add_welfare_type()
    {
        $welfare_type_names = WelfareTypeName::all();
        $data = [
            'welfare_type_names' => $welfare_type_names,
        ];

        return view('welfare.add_welfare_type', $data);
    }


    public function edit(WelfareStatus $welfare_status)
    {

        $welfare_type_names = WelfareTypeName::all();
        $data = [
            'welfare_type_names' => $welfare_type_names,
            'welfare_status' => $welfare_status,
            'status_names' => self::$status_names,
        ];

        return view('welfare.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WelfareStatus $welfare_status)
    {

//        validation to be added


        $welfare_types_from_request = $request->input('welfare_types');
        $welfare_status_mapping_types = $welfare_status->welfare_types;
        $request_types_count = 0;
        $mapping_types_count = 0;

        if ($welfare_types_from_request) {
            $request_types_count = count($welfare_types_from_request);
        }
        if ($welfare_status_mapping_types) {
            $mapping_types_count = count($welfare_status_mapping_types);

        }


        if ($request_types_count == $mapping_types_count) {
            for ($i = 0; $i < $mapping_types_count; $i++) {
                $welfare_status_mapping_type = $welfare_status_mapping_types[$i];
                $welfare_status_mapping_type->welfare_type_name_id = $welfare_types_from_request[$i];
                $welfare_status_mapping_type->update();
            }

        } elseif ($request_types_count > $mapping_types_count) {
//            need to add
            for ($i = 0; $i < $mapping_types_count; $i++) {
                $welfare_status_mapping_type = $welfare_status_mapping_types[$i];
                $welfare_status_mapping_type->welfare_type_name_id = $welfare_types_from_request[$i];
                $welfare_status_mapping_type->update();
            }
            for ($i = $mapping_types_count; $i < $request_types_count; $i++) {
                WelfareType::create([
                    'welfare_type_company_relation_id' => rand(1, 2),
                    'welfare_status_id' => $welfare_status->id,
                    'welfare_type_name_id' => $welfare_types_from_request[$i],
                ]);
            }
        } else {
//            need to delete
            for ($i = 0; $i < $request_types_count; $i++) {
                $welfare_status_mapping_type = $welfare_status_mapping_types[$i];
                $welfare_status_mapping_type->welfare_type_name_id = $welfare_types_from_request[$i];
                $welfare_status_mapping_type->update();
            }
            for ($i = $request_types_count; $i < $mapping_types_count; $i++) {
                $welfare_status_mapping_type = $welfare_status_mapping_types[$i];
                $welfare_status_mapping_type->delete();
            }
        }

        $welfare_status->budget = $request->input('budget');
        $welfare_status->track_status = $request->input('status');
        $welfare_status->update_date = now();
        $welfare_status->update();
        return redirect()->route('welfare_status.index');


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


    public function add_customer_welfare()
    {
        $welfare_type_names = WelfareTypeName::all();
        $customers = Customer::all();

//        $selected_customer = [];
//
//        foreach ($customers as $customer){
//            foreach ($customer->welfarestatus as $customer_welfare_status){
//                if(count($customer_welfare_status->welfare_types)>0){
//                    array_push($selected_customer,$customer);
//                    break;
//
//                }
//
//            }
//        }

//        dd($selected_customer);


        $data = [
            'status_names' => self::$status_names,
            'welfare_type_names' => $welfare_type_names,
            'customers' => $customers,
        ];
        return view('welfare.add_customer_welfare', $data);
    }


    public function get_customer_welfare_status(Request $request)
    {
        $customer_id = $request['customer_select_id'];
//        $welfare_status = WelfareStatus::where('customer_id','=',$customer_id)->get()->pluck('welfare_name','id');
        $welfare_status = WelfareStatus::where('customer_id', '=', $customer_id)->get();

        $arr = [];
        foreach ($welfare_status as $wt) {
            if (count($wt->welfare_types) <= 0) {
                $arr[$wt->id] = $wt->welfare_name;
            }
        }
//        return $welfare_status;
        return $arr;
    }

    public function get_welfare_purpose_budget_status(Request $request)
    {
        $id = $request['welfare_status_id'];
        $welfare_status = WelfareStatus::find($id);
        $arr = [
            'budget' => $welfare_status->budget,
            'track_status' => $welfare_status->track_status,
        ];
        return $arr;
    }


    public function update_customer_welfare(Request $request)
    {
//        dd($request);
        $this->validate($request, [
            'customer_id' => 'required',
            'welfare_status_id' => 'numeric|required|min:1',
            'welfare_types' => 'required',
            'status' => 'required',
        ], [
            'welfare_types.required' => '請選擇要新增之福利類別',
            'customer_id.required' => '請選擇客戶',
            'welfare_status_id.min' => '請選擇一個福利目的',
            'welfare_status_id.required' => '請選擇一個福利目的',
        ]);


        $welfare_status = WelfareStatus::find($request->input('welfare_status_id'));
        if ($request->has('budget')) {
            $welfare_status->budget = $request->input('budget');
        }
        $welfare_status->track_status = $request->input('status');

        foreach ($request->input('welfare_types') as $welfare_type_name_id) {
            WelfareType::Create([
                'welfare_type_name_id' => $welfare_type_name_id,
                'welfare_status_id' => $welfare_status->id,
//                to be deleted
                'welfare_type_company_relation_id' => 1,
            ]);
        }

        $welfare_status->update();
        return redirect()->route('welfare_status.index');


    }

}
