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
    public static  $status_names  = ['Block','Pending','Success'];
    public function index(Request $request)
    {
        $sortBy = 'customer_id';
        $sortBy_text = ['客戶名稱', '目的', '追蹤狀況','預算', '更新日期'];
        $status_filter = -1;


//        0 means show all customers
//        1 means show my customers
        $user_filter = 0;
        $query = WelfareStatus::query();
        $search_type = 0;
        $search_info = '';

        if ($request->has('search_type')) {
            $search_type = $request->query('search_type');
        }
        if ($search_type > 0) {
            $search_info = $request->query('search_info');
            switch ($search_type) {
                case 1:
                    if($search_info!=''){
                        $query->join('customers','welfare_status.customer_id','=','customers.id');
                        $query->orWhere('customers.name','like',"%{$search_info}%");
                    }
                    break;
                case 2:
                    if($search_info!=''){
                        $query->orWhere('welfare_name','like',"%{$search_info}%");
                    }
                    break;
//                case 3:
//                    if($search_info!=''){
//                        $query->join('welfare_types','welfare_status.id','=','welfare_types.welfare_status_id');
//                        $query->join('welfare_type_names','welfare_type_names.id','=','welfare_types.welfare_type_name_id');
//                        $query->orWhere('welfare_type_names.name','like',"%{$search_info}%");
////                        dd($query);
//                    }
//                    break;

                default:
                    break;
            }
        }




        if ($request->has('user_filter')) {
            $user_filter = $request->query('user_filter');
        }
        if ($user_filter > 0) {
            $query->join('customers','welfare_status.customer_id','=','customers.id');
            $query->orWhere('customers.user_id', '=', Auth::user()->id);
        }

        if ($request->has('status_filter')) {
            $status_filter = $request->query('status_filter');
            foreach ($status_filter as $s) {
                if ($s > -1) {
                    $query->orWhere('track_status', '=', $s);
                }
            }
        }

        if ($request->has('sortBy')) {
            $sortBy = $request->query('sortBy');
            foreach ($sortBy as $q) {
                $query->orderBy($q,'DESC');

            }
        }
        else{
            $query->orderBy($sortBy);
        }


        $welfare_statuses = $query->paginate(15);

        $data = [
            'status_names'=>self::$status_names,
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

    public function store_welfare_type(Request $request)
    {

        if($request->input('to_be_delete')){
//            delete some
            $buf = 1;
        }

        foreach ($request->input('add_welfare_type_names') as $name){
            WelfareTypeName::create([
                'name' => $name,
            ]);
        }
        return redirect()->route('welfare_status.index');
    }

    public function add_welfare_type()
    {
        $welfare_type_names = WelfareTypeName::all();
        $data = [
            'welfare_type_names'=> $welfare_type_names,
        ];

        return view('welfare.add_welfare_type',$data);
    }



    public function edit(WelfareStatus $welfare_status)
    {

        $welfare_type_names = WelfareTypeName::all();
        $data = [
            'welfare_type_names'=> $welfare_type_names,
            'welfare_status' => $welfare_status,
            'status_names'=>self::$status_names,
        ];

        return view('welfare.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WelfareStatus $welfare_status)
    {

//        validation to be added



        $welfare_types_from_request = $request->input('welfare_types');
        $welfare_status_mapping_types = $welfare_status->welfare_types;

        $request_types_count = count($welfare_types_from_request);
        $mapping_types_count = count($welfare_status_mapping_types);


        if($request_types_count == $mapping_types_count){
            for($i=0;$i<$mapping_types_count;$i++){
                $welfare_status_mapping_type = $welfare_status_mapping_types[$i];
                $welfare_status_mapping_type->welfare_type_name_id = $welfare_types_from_request[$i];
                $welfare_status_mapping_type->update();
            }

        }
        elseif ($request_types_count > $mapping_types_count ){
//            need to add
            for($i=0;$i<$mapping_types_count;$i++){
                $welfare_status_mapping_type = $welfare_status_mapping_types[$i];
                $welfare_status_mapping_type->welfare_type_name_id = $welfare_types_from_request[$i];
                $welfare_status_mapping_type->update();
            }
            for($i=$mapping_types_count;$i<$request_types_count;$i++){
                WelfareType::create([
                    'welfare_type_company_relation_id'=>rand(1,2),
                    'welfare_status_id' => $welfare_status->id,
                    'welfare_type_name_id'=>$welfare_types_from_request[$i],
                ]);
            }
        }
        else{
//            need to delete
            for($i=0;$i<$request_types_count;$i++){
                $welfare_status_mapping_type = $welfare_status_mapping_types[$i];
                $welfare_status_mapping_type->welfare_type_name_id = $welfare_types_from_request[$i];
                $welfare_status_mapping_type->update();
            }
            for($i=$request_types_count;$i<$mapping_types_count;$i++){
                $welfare_status_mapping_type = $welfare_status_mapping_types[$i];
                $welfare_status_mapping_type->delete();
            }
        }

        $welfare_status->budget = $request->input('budget');
        $welfare_status->track_status = $request->input('status');
        $welfare_status->update_date = now();
        $welfare_status->update();
        return redirect()->route('welfare_status.index');
//        return redirect()->back();


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