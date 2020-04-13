<?php

namespace App\Http\Controllers;

use App\Category;
use App\ConcatRecord;
use App\Customer;
use App\Member;
use App\Sale;
use App\Type;
use App\WelfareStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Input;

class DashboardController extends Controller
{
    public function index()
    {

//        its for concat record
        $query = Customer::query();
        $query->join('concat_records','customers.id','=','concat_records.customer_id');
//        $query->join('business_concat_persons','business_concat_persons.customer_id','=','customers.id');

        if(Auth::user()->level==2){
            $query->Where('concat_records.status','=','1');

        }
        else{
            $query->Where('customers.user_id','=',Auth::user()->id)->Where('concat_records.status','=','1');

        }
        $query->orderBy('concat_records.update_date','DESC');

//        $query->select('customers.id as customer_id','customers.name as customer_name',
//            'concat_records.status as track_status','track_date','business_concat_persons.name as concat_person_name','concat_records.track_content',
//            'business_concat_persons.email as concat_person_email', 'business_concat_persons.phone_number as concat_person_phone_number',
//            'concat_records.id as concat_record_id'
//            );

        $query->select('customers.id as customer_id','customers.name as customer_name',
            'concat_records.status as track_status','track_date','concat_records.track_content',
            'concat_records.id as concat_record_id'
        );


        $customers = $query->get();



        $count= count($customers);
        $rev = '6';
        $sums = ceil($count/$rev);

        $page = Input::get('page');
        if(empty($page)){
            $page = "1";
        }
        $prev = ($page-1)>0?$page-1:1;
        $next = ($page+1)<$sums?$page+1:$sums;
        $offset = ($page-1)*$rev;
        $customers = $query->skip($offset)->limit($rev)->get();
        $pp = array();
        for($i=1;$i<=$sums;$i++){
            $pp[$i]=$i;
        }





        //        its for welfare
        $query = WelfareStatus::query();
        $query->join('customers','welfare_status.customer_id','=','customers.id');
//        $query->join('business_concat_persons','business_concat_persons.customer_id','=','customers.id');

        if(Auth::user()->level==2){
            $query->Where('welfare_status.track_status','=','3');
        }
        else{
            $query->Where('customers.user_id','=',Auth::user()->id)->Where('welfare_status.track_status','=','3');
        }
        $query->orderBy('welfare_status.update_date','DESC')->orderBy('customer_id','DESC');

        $query->select('customers.id as customer_id','customers.name as customer_name','welfare_status.id',
            'welfare_status.track_status','budget','welfare_status.welfare_name');

        $welfare_stautses = $query->get();
//        dd($welfare_stautses);

//        $origin_welfare_statuses = WelfareStatus::findMany([]);

        $wcount= count($welfare_stautses);
        $wrev = '6';
        $wsums = ceil($wcount/$wrev);

        $wpage = Input::get('wpage');
        if(empty($wpage)){
            $wpage = "1";
        }
        $wprev = ($wpage-1)>0?$wpage-1:1;
        $wnext = ($wpage+1)<$wsums?$wpage+1:$wsums;
        $woffset = ($wpage-1)*$wrev;
        $welfare_stautses = $query->skip($woffset)->limit($wrev)->get();



        $wpp = array();
        for($i=1;$i<=$wsums;$i++){
            $wpp[$i]=$i;
        }


        $data = [
            'customers' => $customers,
            'count' => $count,
            'rev' => $rev,
            'prev'=>$prev,
            'next'=>$next,
            'sums'=>$sums,
            'pp'=>$pp,
            'page'=>$page,
            'welfare_statuses'=>$welfare_stautses,
            'wcount' => $wcount,
            'wrev' => $wrev,
            'wprev'=>$wprev,
            'wnext'=>$wnext,
            'wsums'=>$wsums,
            'wpp'=>$wpp,
            'wpage'=>$wpage,

        ];
        return view('dashboard.index' , $data);
    }




    public function getPage(Request $request){

        $query = Customer::query();
        $query->join('concat_records','customers.id','=','concat_records.customer_id');
//        $query->join('business_concat_persons','business_concat_persons.customer_id','=','customers.id');
        if(Auth::user()->level==2){
            $query->Where('concat_records.status','=','1');

        }
        else{
            $query->Where('customers.user_id','=',Auth::user()->id)->Where('concat_records.status','=','1');

        }
        $query->orderBy('concat_records.update_date','DESC');


//        $query->select('customers.id as customer_id','customers.name as customer_name',
//            'concat_records.status as track_status','track_date','business_concat_persons.name as concat_person_name','concat_records.track_content',
//            'business_concat_persons.email as concat_person_email', 'business_concat_persons.phone_number as concat_person_phone_number',
//            'concat_records.id as concat_record_id'
//        );
        $query->select('customers.id as customer_id','customers.name as customer_name',
            'concat_records.status as track_status','track_date','concat_records.track_content',
            'concat_records.id as concat_record_id'
        );

        $customers = $query->get();
        $count= count($customers);
        $rev = '6';
        $sums = ceil($count/$rev);
        $page = Input::get('page');
        if(empty($page)){
            $page = "1";
        }
        $prev = ($page-1)>0?$page-1:1;
        $next = ($page+1)<$sums?$page+1:$sums;
        $offset = ($page-1)*$rev;
        $customers = $query->skip($offset)->limit($rev)->get();
        $pp = array();
        for($i=1;$i<=$sums;$i++){
            $pp[$i]=$i;
        }
        $res = ' <table class="table table-bordered table-hover">
                                    <thead style="background-color: lightgray" style="width: 100%">
                                    <tr>
                                        <th class="text-center" style="width: 5%">狀態</th>
                                            <th class="text-center" style="width: 30%">客戶名稱</th>
                                            <th class="text-center" style="width: 15%">Email</th>
                                            <th class="text-center" style="width: 15%">Concat Info</th>
                                            <th class="text-center" style="width: 30%">Note</th>
                                    </tr>
                                    </thead>';
        foreach($customers as $customer){
            $res .= '<tr ondblclick="window.location.href = \'/customers/' . $customer->customer_id . '/record\' " class="text-center">';

            $res .= '<td><input type="checkbox" id="' . $customer->concat_record_id . '"
                                                           name="change_record_status" ></td>';
            $res .= '<td>'.$customer->customer_name.'</td>';
            $res .= '<td>'.\App\BusinessConcatPerson::where('customer_id','=',$customer->customer_id)->orderBy('update_date','DESC')->first()->email.'</td>';
            $res .= '<td>'.\App\BusinessConcatPerson::where('customer_id','=',$customer->customer_id)->orderBy('update_date','DESC')->first()->phone_number.'</td>';
            $res .= '<td>'.$customer->track_content.'</td>';
            $res .= '</tr>';
        }
        $res.= '</table>';

        $res .='<div class="page">';
        $res .='<!-------分页---------->' ;
        if($count > $rev){
            $res .= '<ul class="pagination">';
            if($page != 1){
                $res .='<li >';
                $res .='<a href="javascript:void(0)" onclick="page('.$prev.')"><<</a>';
                $res .='</li>';
            }
            foreach($pp as $k=>$v){
                if($v == $page){
                    $res .='<li class="active"><span>'.$v.'</span></li>';

                }else{
                    $res .='<li >' ;
                    $res .='<a href="javascript:void(0)" onclick="page('.$v.')">'.$v.'</a>' ;
                    $res .='</li>' ;
                }

            }
            if($page != $sums){
                $res .= '<li>';
                $res .= "<a href='javascript:void(0)' onclick='page(".$next.")'>>></a>" ;
                $res .= '</li>';
            }
            $res .='</ul>';
        }
        return $res;
    }




    public function getwPage(Request $request){

        //        its for welfare
        $query = WelfareStatus::query();
        $query->join('customers','welfare_status.customer_id','=','customers.id');
//        $query->join('business_concat_persons','business_concat_persons.customer_id','=','customers.id');

        if(Auth::user()->level==2){
            $query->Where('welfare_status.track_status','=','3');
        }
        else{
            $query->Where('customers.user_id','=',Auth::user()->id)->Where('welfare_status.track_status','=','3');
        }
        $query->orderBy('welfare_status.update_date','DESC');

        $query->select('customers.id as customer_id','customers.name as customer_name',
            'welfare_status.track_status','budget','welfare_status.welfare_name','welfare_status.id as welfare_status_id');

        $welfare_statuses = $query->get();

        $wcount= count($welfare_statuses);
        $wrev = '6';
        $wsums = ceil($wcount/$wrev);

        $wpage = Input::get('wpage');
        if(empty($wpage)){
            $wpage = "1";
        }
        $wprev = ($wpage-1)>0?$wpage-1:1;
        $wnext = ($wpage+1)<$wsums?$wpage+1:$wsums;
        $woffset = ($wpage-1)*$wrev;
        $welfare_statuses = $query->skip($woffset)->limit($wrev)->get();
        $wpp = array();
        for($i=1;$i<=$wsums;$i++){
            $wpp[$i]=$i;
        }

        $res = ' <table class="table table-bordered table-hover">
                                    <thead style="background-color: lightgray">
                                    <tr>
                                        <th class="text-center" style="width: 150px">客戶名稱</th>
                                        <th class="text-center" style="width: 150px">福利目的</th>
                                        <th class="text-center" style="width: 150px">福利類別</th>
                                        <th class="text-center" style="width: 120px">預算</th>
                                    </tr>
                                    </thead>';
        foreach($welfare_statuses as $welfare_status){
            $res .= '<tr ondblclick="window.location.href = \'/welfare_status/' . $welfare_status->welfare_status_id . '/edit\' " class="text-center">';
            $res .= '<td>'.$welfare_status->customer_name.'</td>';
            $res .= '<td>'.$welfare_status->welfare_name.'</td>';
            $res .='<td>';

            foreach ($welfare_status->welfare_types as $welfare_type){
                $res.= $welfare_type->welfare_type_name->name;
            }
            $res.= '</td>';
            $res .= '<td>'.$welfare_status->budget.'</td>';
            $res .= '</tr>';
        }
        $res.= '</table>';

        $res .='<div class="page">';
        $res .='<!-------分页---------->' ;
        if($wcount > $wrev){
            $res .= '<ul class="pagination">';
            if($wpage != 1){
                $res .='<li >';
                $res .='<a href="javascript:void(0)" onclick="wpage('.$wprev.')"><<</a>';
                $res .='</li>';
            }
            foreach($wpp as $k=>$v){
                if($v == $wpage){
                    $res .='<li class="active"><span>'.$v.'</span></li>';

                }else{
                    $res .='<li >' ;
                    $res .='<a href="javascript:void(0)" onclick="wpage('.$v.')">'.$v.'</a>' ;
                    $res .='</li>' ;
                }

            }
            if($wpage != $wsums){
                $res .= '<li>';
                $res .= "<a href='javascript:void(0)' onclick='wpage(".$wnext.")'>>></a>" ;
                $res .= '</li>';
            }
            $res .='</ul>';
        }
        return $res;
    }


    public function set_concat_status(Request $request)
    {

        if($request['ids']){
            foreach($request['ids'] as $id){
                $concat_record = ConcatRecord::find($id);
                $concat_record->status = 0;
                $concat_record->update();

            }
        }

        return $request['page'];


    }











}

