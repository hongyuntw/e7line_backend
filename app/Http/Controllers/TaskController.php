<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Task;
use App\TaskAssignment;
use App\TaskReplyMsg;
use App\User;
use App\WelfareType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class TaskController extends Controller
{

    public static $task_status_names = ['未處理','已處理/待確認','已完成'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        if(Auth::user()->level==2){
            $query = Task::query();
            $user_filter = -1;
            $status_filter = -1;
            $search_type = -1;
            $search_info = -1;

            $asms_query = TaskAssignment::query();
            //        user
            if($request->has('user_filter')){
                $user_filter = $request->input('user_filter');
            }
            if($user_filter>0) {
                $asms_query->where('user_id','=',$user_filter);
            }
            // status
            if ($request->has('status_filter')) {
                $status_filter = $request->input('status_filter');
            }
            if((int)$status_filter>=0){
                $asms_query->where('status', '=', (int)$status_filter);
            }

            $query->whereIn('id',$asms_query->pluck('task_id')->toArray());
            if ($request->has('search_type')) {
                $search_type = $request->query('search_type');
            }
            if ($search_type > 0) {
                $search_info = $request->query('search_info');
                switch ($search_type) {
                    case 1:
                        $query->where('tasks.topic', 'like', "%{$search_info}%");
                        break;
                    case 2:
                        $query->where('tasks.content', 'like', "%{$search_info}%");
                        break;
                    default:
                        break;
                }
            }




            $query->where('is_deleted','=',0);
            $query->orderBy('tasks.create_date','DESC');
            $tasks = $query->paginate(15);

            $data = [
                'tasks' => $tasks,
                'task_status_names' => self::$task_status_names,
                'user_filter' => $user_filter,
                'status_filter' => $status_filter,
                'search_type' => $search_type,
                'search_info'  => $search_info,
                'users' => User::all(),
            ];

            return view('tasks.index',$data);

        }
        else{

            $query = TaskAssignment::query();
            $query->where('user_id','=',Auth::user()->id);
            $query->where('is_deleted','=',0);
            $query->orderBy('task_assignments.create_date','DESC');
            $query->where('status','=',0);
            $processAsms = $query->get();


            $process_count= count($processAsms);
            $process_rev = '6';
            $process_sums = ceil($process_count/$process_rev);
            session_start();
            $process_page = $request->input('process_page');
            if(empty($process_page)){
                $process_page = Session::get('process_page');
                Session::remove('process_page');
                if(empty($process_page)){
                    $process_page = "1";
                }
            }
            session_write_close();
            $process_prev = ($process_page-1) >0 ? $process_page-1 :1;
            $process_next = ($process_page+1)<$process_sums?$process_page+1:$process_sums;
            $process_offset = ($process_page-1)*$process_rev;
            $processAsms = $query->skip($process_offset)->limit($process_rev)->get();
            $process_pp = array();
            for($i=1;$i<=$process_sums;$i++){
                $process_pp[$i]=$i;
            }




            $query_1 = TaskAssignment::query();
            $query_1->where('user_id','=',Auth::user()->id);
            $query_1->where('is_deleted','=',0);
            $query_1->orderBy('task_assignments.create_date','DESC');
            $query_1->where('status','=',1);
            $needToCheckAsms = $query_1->get();
            $check_count= count($needToCheckAsms);
            $check_rev = '6';
            $check_sums = ceil($check_count/$check_rev);
            session_start();
            $check_page = $request->input('check_page');
            if(empty($check_page)){
                $check_page = Session::get('check_page');
                Session::remove('check_page');
                if(empty($check_page)){
                    $check_page = "1";
                }
            }
            session_write_close();
            $check_prev = ($check_page-1) >0 ? $check_page-1 :1;
            $check_next = ($check_page+1)<$check_sums?$check_page+1:$check_sums;
            $check_offset = ($check_page-1)*$check_rev;
            $needToCheckAsms = $query_1->skip($check_offset)->limit($check_rev)->get();
            $check_pp = array();
            for($i=1;$i<=$check_sums;$i++){
                $check_pp[$i]=$i;
            }






            $query_2 = TaskAssignment::query();
            $query_2->where('user_id','=',Auth::user()->id);
            $query_2->where('is_deleted','=',0);
            $query_2->orderBy('task_assignments.create_date','DESC');
            $query_2->where('status','=',2);
            $doneAsms = $query_2->get();

            $done_count= count($doneAsms);
            $done_rev = '6';
            $done_sums = ceil($done_count/$done_rev);
            session_start();
            $done_page = $request->input('done_page');
            if(empty($done_page)){
                $done_page = Session::get('done_page');
                Session::remove('done_page');
                if(empty($done_page)){
                    $done_page = "1";
                }
            }
            session_write_close();
            $done_prev = ($done_page-1) >0 ? $done_page-1 :1;
            $done_next = ($done_page+1)<$done_sums?$done_page+1:$done_sums;
            $done_offset = ($done_page-1)*$done_rev;
            $doneAsms = $query_2->skip($done_offset)->limit($done_rev)->get();
            $done_pp = array();
            for($i=1;$i<=$done_sums;$i++){
                $done_pp[$i]=$i;
            }



            $data = [
                'doneAsms' => $doneAsms,
                'processAsms'=> $processAsms,
                'needToCheckAsms' => $needToCheckAsms,
                'process_count' => $process_count,
                'process_rev' => $process_rev,
                'process_prev'=>$process_prev,
                'process_next'=>$process_next,
                'process_sums'=>$process_sums,
                'process_pp'=>$process_pp,
                'process_page'=>$process_page,


                'check_count' => $check_count,
                'check_rev' => $check_rev,
                'check_prev'=>$check_prev,
                'check_next'=>$check_next,
                'check_sums'=>$check_sums,
                'check_pp'=>$check_pp,
                'check_page'=>$check_page,

                'done_count' => $done_count,
                'done_rev' => $done_rev,
                'done_prev'=>$done_prev,
                'done_next'=>$done_next,
                'done_sums'=>$done_sums,
                'done_pp'=>$done_pp,
                'done_page'=>$done_page,
            ];


            return view('tasks.indexNormal',$data);
        }

    }


    public function setPageSession(Request $request)
    {
        if($request->has('process_page')){
            session_start();
            Session::remove('process_page');
            Session::put('process_page',$request->input('process_page'));
            session_write_close();  //<---------- Add this to close the session so that reading from the session will contain the new value.
            return 'success set page to session to' . $request->input('process_page');

        }
        if($request->has('check_page')){
            session_start();
            Session::remove('check_page');
            Session::put('check_page',$request->input('check_page'));
            session_write_close();  //<---------- Add this to close the session so that reading from the session will contain the new value.
            return 'success set page to session to' . $request->input('check_page');


        }
        if($request->has('done_page')){
            session_start();
            Session::remove('done_page');
            Session::put('done_page',$request->input('done_page'));
            session_write_close();  //<---------- Add this to close the session so that reading from the session will contain the new value.
            return 'success set page to session to' . $request->input('done_page');
        }
    }


    public function getProcessPage(Request $request){

        $query = TaskAssignment::query();
        $query->where('user_id','=',Auth::user()->id);
        $query->where('is_deleted','=',0);
        $query->orderBy('task_assignments.create_date','DESC');
        $query->where('status','=',0);
        $processAsms = $query->get();

        $count= count($processAsms);
        $rev = '6';
        $sums = ceil($count/$rev);
        $page = Input::get('page');
        if(empty($page)){
            $page = "1";
        }
        $prev = ($page-1)>0?$page-1:1;
        $next = ($page+1)<$sums?$page+1:$sums;
        $offset = ($page-1)*$rev;
        $processAsms = $query->skip($offset)->limit($rev)->get();

        if(count($processAsms)==0 && $page>1){
            $page -= 1;
            $prev = ($page-1)>0?$page-1:1;
            $next = ($page+1)<$sums?$page+1:$sums;
            $offset = ($page-1)*$rev;
            $processAsms = $query->skip($offset)->limit($rev)->get();

        }
        $pp = array();
        for($i=1;$i<=$sums;$i++){
            $pp[$i]=$i;
        }

        $res = '                                    <table class="table table-striped" style="width: 100%">
                                        <thead style="background-color: lightgray">
                                        <tr class="text-center">
                                            <th class="text-center" style="width: 10%;">主題</th>
                                            <th class="text-center" style="width: 10%;">任務內容</th>
                                            <th class="text-center" style="width: 20%;">訊息</th>
                                            <th class="text-center" style="width: 20%;">回覆／完成任務</th>

                                        </tr>
                                        </thead>';
        foreach ($processAsms as $asm){
            $res.= '<tr>';
            $res .= '<td class="text-center">'.$asm->task->topic.'</td>';
            $res .= '<td class="text-center">'.$asm->task->content.'</td>';
            $res .= '<td style="text-align: center"> <ul class="fa-ul" style="display: inline-block">';
            foreach ($asm->task_reply_msgs()->orderBy('create_date','DESC')->get() as $msg){
                $res .= '<li>';
                if($msg->user_id == Auth::user()->id){
                    $res .= '<i class="fa fa-li fa-mail-forward" style="color: green"></i>';
                }
                else {
                    $res .= ' <i class="fa fa-li fa-mail-reply" style="color: red"></i>';

                }
                $res .= $msg->text.'&nbsp &nbsp<span style="float: right">' . date("Y/m/d H:i", strtotime($msg->update_date));


                if($msg->can_delete && $msg->user_id == Auth::user()->id){
                    $res .=  '<span style="float: right;">
                                                                        <a id="'.$msg->id.'_msg" style="color:darkred;text-shadow:0 1px 0 #fff;
                                                                                font-weight: 700;opacity: 2;cursor: pointer"
                                                                           onclick="delete_msg(this.id)">x</a>
                                                                    </span>';
                }
                $res .= '</span>';

                $res .= '</li>';
            }
            $res .= '</ul>';
            $res .= '</td> <td style="text-align: center">
                                                    <div class="input-group" style="display: inline-block">
                                                        <textarea class="form-control"
                                                                  id="'.$asm->id .'_textbox"></textarea>
                                                        <button class="form-control" id="'.$asm->id .'"
                                                                onclick="taskComplete(this)"
                                                                style="background-color: forestgreen;color: white">完成
                                                        </button>
                                                    </div>


                                                </td>

                                            </tr>';
        }
        $res .= ' </table>';


        $res .='<!-------分页---------->' ;
        if($count > $rev){
            $res .= '<ul class="pagination">';
            if($page != 1){
                $res .='<li >';
                $res .='<a href="javascript:void(0)" onclick="process_page('.$prev.')"><<</a>';
                $res .='</li>';
            }
            $flag = true;
            foreach($pp as $k=>$v){
                if($v == $page){
                    $res .='<li class="active"><span>'.$v.'</span></li>';

                }
                elseif (abs($v-$page)>=3 && $v<$page){
                    if($v==1){
                        $res.='<li>
                                                            <a href="javascript:void(0)"
                                                               onclick="process_page('.$v.')">'.$v.'</a>
                                                        </li>';
                    }
                    else{
                        if($flag){
                            $res.= '<li><span>...</span></li>';
                            $flag = false;
                        }

                    }
                }
                elseif(abs($v-$page)>=3 && $v>$page){
                    if($v==count($pp)){
                        $res.='<li>
                                                            <a href="javascript:void(0)"
                                                               onclick="process_page('.$v.')">'.$v.'</a>
                                                        </li>';
                    }
                    else{
                        if($flag){
                            $res.= '<li><span>...</span></li>';
                            $flag = false;
                        }

                    }
                }
                else{
                    $flag = true;
                    $res .='<li >' ;
                    $res .='<a href="javascript:void(0)" onclick="process_page('.$v.')">'.$v.'</a>' ;
                    $res .='</li>' ;
                }

            }
            if($page != $sums){
                $res .= '<li>';
                $res .= "<a href='javascript:void(0)' onclick='process_page(".$next.")'>>></a>" ;
                $res .= '</li>';
            }
            $res .='</ul>';
        }
        return $res;
    }



    public function getCheckPage(Request $request){

        $query = TaskAssignment::query();
        $query->where('user_id','=',Auth::user()->id);
        $query->where('is_deleted','=',0);
        $query->orderBy('task_assignments.create_date','DESC');
        $query->where('status','=',1);
        $needToCheckAsms = $query->get();

        $count= count($needToCheckAsms);
        $rev = '6';
        $sums = ceil($count/$rev);
        $page = Input::get('page');
        if(empty($page)){
            $page = "1";
        }
        $prev = ($page-1)>0?$page-1:1;
        $next = ($page+1)<$sums?$page+1:$sums;
        $offset = ($page-1)*$rev;
        $needToCheckAsms = $query->skip($offset)->limit($rev)->get();

        if(count($needToCheckAsms)==0 && $page>1){
            $page -= 1;
            $prev = ($page-1)>0?$page-1:1;
            $next = ($page+1)<$sums?$page+1:$sums;
            $offset = ($page-1)*$rev;
            $needToCheckAsms = $query->skip($offset)->limit($rev)->get();

        }
        $pp = array();
        for($i=1;$i<=$sums;$i++){
            $pp[$i]=$i;
        }

        $res = '                                    <table class="table table-striped" style="width: 100%">
                                        <thead style="background-color: lightgray">
                                        <tr class="text-center">
                                            <th class="text-center" style="width: 10%;">主題</th>
                                            <th class="text-center" style="width: 10%;">任務內容</th>
                                            <th class="text-center" style="width: 20%;">訊息</th>
                                            <th class="text-center" style="width: 20%;">其他</th>

                                        </tr>
                                        </thead>';
        foreach ($needToCheckAsms as $asm){
            $res.= '<tr>';
            $res .= '<td class="text-center">'.$asm->task->topic.'</td>';
            $res .= '<td class="text-center">'.$asm->task->content.'</td>';
            $res .= '<td style="text-align: center"> <ul class="fa-ul" style="display: inline-block">';
            foreach ($asm->task_reply_msgs()->orderBy('create_date','DESC')->get() as $msg){
                $res .= '<li>';
                if($msg->user_id == Auth::user()->id){
                    $res .= '<i class="fa fa-li fa-mail-forward" style="color: green"></i>';
                }
                else {
                    $res .= ' <i class="fa fa-li fa-mail-reply" style="color: red"></i>';

                }
                $res .= $msg->text.'&nbsp &nbsp<span style="float: right">' . date("Y/m/d H:i", strtotime($msg->update_date)) . '</span>';
                $res .= '</li>';
            }
            $res .= '</ul>';
            $res .= '</td> <td style="text-align: center;vertical-align: middle">
                                                    <a onclick="taskBackToProcess('.$asm->id.')"
                                                       class="btn btn-sm btn-warning">
                                                        退回未處理
                                                    </a>
                                                </td>

                                            </tr>';
        }
        $res .= ' </table>';


        $res .='<!-------分页---------->' ;
        if($count > $rev){
            $res .= '<ul class="pagination">';
            if($page != 1){
                $res .='<li >';
                $res .='<a href="javascript:void(0)" onclick="check_page('.$prev.')"><<</a>';
                $res .='</li>';
            }
            $flag = true;
            foreach($pp as $k=>$v){
                if($v == $page){
                    $res .='<li class="active"><span>'.$v.'</span></li>';

                }
                elseif (abs($v-$page)>=3 && $v<$page){
                    if($v==1){
                        $res.='<li>
                                                            <a href="javascript:void(0)"
                                                               onclick="check_page('.$v.')">'.$v.'</a>
                                                        </li>';
                    }
                    else{
                        if($flag){
                            $res.= '<li><span>...</span></li>';
                            $flag = false;
                        }

                    }
                }
                elseif(abs($v-$page)>=3 && $v>$page){
                    if($v==count($pp)){
                        $res.='<li>
                                                            <a href="javascript:void(0)"
                                                               onclick="check_page('.$v.')">'.$v.'</a>
                                                        </li>';
                    }
                    else{
                        if($flag){
                            $res.= '<li><span>...</span></li>';
                            $flag = false;
                        }

                    }
                }
                else{
                    $flag = true;
                    $res .='<li >' ;
                    $res .='<a href="javascript:void(0)" onclick="check_page('.$v.')">'.$v.'</a>' ;
                    $res .='</li>' ;
                }

            }
            if($page != $sums){
                $res .= '<li>';
                $res .= "<a href='javascript:void(0)' onclick='check_page(".$next.")'>>></a>" ;
                $res .= '</li>';
            }
            $res .='</ul>';
        }
        return $res;
    }


    public function getDonePage(Request $request){

        $query = TaskAssignment::query();
        $query->where('user_id','=',Auth::user()->id);
        $query->where('is_deleted','=',0);
        $query->orderBy('task_assignments.create_date','DESC');
        $query->where('status','=',2);
        $DoneAsms = $query->get();

        $count= count($DoneAsms);
        $rev = '6';
        $sums = ceil($count/$rev);
        $page = Input::get('page');
        if(empty($page)){
            $page = "1";
        }
        $prev = ($page-1)>0?$page-1:1;
        $next = ($page+1)<$sums?$page+1:$sums;
        $offset = ($page-1)*$rev;
        $DoneAsms = $query->skip($offset)->limit($rev)->get();

        if(count($DoneAsms)==0 && $page>1){
            $page -= 1;
            $prev = ($page-1)>0?$page-1:1;
            $next = ($page+1)<$sums?$page+1:$sums;
            $offset = ($page-1)*$rev;
            $DoneAsms = $query->skip($offset)->limit($rev)->get();

        }
        $pp = array();
        for($i=1;$i<=$sums;$i++){
            $pp[$i]=$i;
        }

        $res = '                                    <table class="table table-striped" style="width: 100%">
                                        <thead style="background-color: lightgray">
                                        <tr class="text-center">
                                            <th class="text-center" style="width: 10%;">主題</th>
                                            <th class="text-center" style="width: 10%;">任務內容</th>
                                            <th class="text-center" style="width: 20%;">訊息</th>

                                        </tr>
                                        </thead>';
        foreach ($DoneAsms as $asm){
            $res.= '<tr>';
            $res .= '<td class="text-center">'.$asm->task->topic.'</td>';
            $res .= '<td class="text-center">'.$asm->task->content.'</td>';
            $res .= '<td style="text-align: center"> <ul class="fa-ul" style="display: inline-block">';
            foreach ($asm->task_reply_msgs()->orderBy('create_date','DESC')->get() as $msg){
                $res .= '<li>';
                if($msg->user_id == Auth::user()->id){
                    $res .= '<i class="fa fa-li fa-mail-forward" style="color: green"></i>';
                }
                else {
                    $res .= ' <i class="fa fa-li fa-mail-reply" style="color: red"></i>';

                }
                $res .= $msg->text.'&nbsp &nbsp<span style="float: right">' . date("Y/m/d H:i", strtotime($msg->update_date)) . '</span>';
                $res .= '</li>';
            }
            $res .= '</ul>';
            $res .= '</td>

                                            </tr>';
        }
        $res .= ' </table>';


        $res .='<!-------分页---------->' ;
        if($count > $rev){
            $res .= '<ul class="pagination">';
            if($page != 1){
                $res .='<li >';
                $res .='<a href="javascript:void(0)" onclick="done_page('.$prev.')"><<</a>';
                $res .='</li>';
            }
            $flag = true;
            foreach($pp as $k=>$v){
                if($v == $page){
                    $res .='<li class="active"><span>'.$v.'</span></li>';

                }
                elseif (abs($v-$page)>=3 && $v<$page){
                    if($v==1){
                        $res.='<li>
                                                            <a href="javascript:void(0)"
                                                               onclick="done_page('.$v.')">'.$v.'</a>
                                                        </li>';
                    }
                    else{
                        if($flag){
                            $res.= '<li><span>...</span></li>';
                            $flag = false;
                        }

                    }
                }
                elseif(abs($v-$page)>=3 && $v>$page){
                    if($v==count($pp)){
                        $res.='<li>
                                                            <a href="javascript:void(0)"
                                                               onclick="done_page('.$v.')">'.$v.'</a>
                                                        </li>';
                    }
                    else{
                        if($flag){
                            $res.= '<li><span>...</span></li>';
                            $flag = false;
                        }

                    }
                }
                else{
                    $flag = true;
                    $res .='<li >' ;
                    $res .='<a href="javascript:void(0)" onclick="done_page('.$v.')">'.$v.'</a>' ;
                    $res .='</li>' ;
                }

            }
            if($page != $sums){
                $res .= '<li>';
                $res .= "<a href='javascript:void(0)' onclick='done_page(".$next.")'>>></a>" ;
                $res .= '</li>';
            }
            $res .='</ul>';
        }
        return $res;
    }



    public function taskComplete(Request $request)
    {

        $taskAsm = TaskAssignment::find($request->input('task_id'));
        $text = $request->input('msg');
//        dd($msg);
        if($text == '' || $text == null){
            $taskAsm->status = 1;
            $taskAsm->update_date = now();
            $taskAsm->update();
        }
        else{
            $taskAsm->status = 1;
            $taskAsm->update_date = now();
            $taskAsm->return_nums =  $taskAsm->return_nums + 1;
            $task_msg = TaskReplyMsg::create([
                'text' => $text,
                'task_assignment_id'=> $taskAsm->id,
                'user_id' => Auth::user()->id,
                'create_date' => now(),
                'update_date' =>now(),
            ]);
            $taskAsm->update();


        }
//        dd($taskAsm);
        $process_page = Session::get('process_page');
        if(empty($process_page)){
            $process_page = "1";
        }
        $check_page = Session::get('check_page');
        if(empty($check_page)){
            $check_page = "1";
        }
        return [
            'process_page' => $process_page,
            'check_page' => $check_page,
        ];

    }

    public function taskChecked(Request $request)
    {
        $taskAsm = TaskAssignment::find($request->input('task_id'));
        $text = $request->input('msg');
//        dd($msg);
        if($text == '' || $text == null){
            $taskAsm->status = 2;
            $taskAsm->update_date = now();
            $taskAsm->update();
        }
        else{
            $taskAsm->status = 2;
            $taskAsm->update_date = now();
            $taskAsm->return_nums =  $taskAsm->return_nums + 1;
            $task_msg = TaskReplyMsg::create([
                'text' => $text,
                'task_assignment_id'=> $taskAsm->id,
                'user_id' => Auth::user()->id,
                'create_date' => now(),
                'update_date' =>now(),
            ]);
            $taskAsm->update();
        }
//        dd($taskAsm);
        return "success";
    }

//    this only root can access
    public function taskBack(Request $request)
    {
        $taskAsm = TaskAssignment::find($request->input('task_id'));
        $text = $request->input('msg');
        if($text == '' || $text == null){
            $taskAsm->status = 0;
            $taskAsm->update_date = now();
            $taskAsm->update();
            foreach ($taskAsm->task_reply_msgs as $msg){
                $msg->can_delete = 0;
                $msg->update();
            }
        }
        else{
            $taskAsm->status = 0;
            $taskAsm->update_date = now();
            $taskAsm->return_nums =  $taskAsm->return_nums + 1;
            foreach ($taskAsm->task_reply_msgs as $msg){
                $msg->can_delete = 0;
                $msg->update();
            }
            $task_msg = TaskReplyMsg::create([
                'text' => $text,
                'task_assignment_id'=> $taskAsm->id,
                'user_id' => Auth::user()->id,
                'create_date' => now(),
                'update_date' =>now(),
            ]);
            $taskAsm->update();
        }
        return "success";
    }

    public function taskBackToProcess(Request $request)
    {
        $taskAsm = TaskAssignment::find($request->input('task_id'));
        $taskAsm->status = 0;
        $taskAsm->update_date = now();

//        $msg = $taskAsm->task_reply_msgs()->orderBy('create_date','DESC')->first();
//        $diff = abs(strtotime($taskAsm->update_date) - strtotime($msg->create_date));
//        dump($msg->create_date);
//        dump($taskAsm->update_date);
//        dd($diff);
        $taskAsm->update();




        $process_page = Session::get('process_page');
        if(empty($process_page)){
            $process_page = "1";
        }
        $check_page = Session::get('check_page');
        if(empty($check_page)){
            $check_page = "1";
        }
        return [
            'process_page' => $process_page,
            'check_page' => $check_page,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = [
            'users' => User::all(),
        ];
        return view('tasks.create',$data);
    }


    public function deleteMsg(Request $request)
    {
        $msg = TaskReplyMsg::find($request->input('msg_id'));
        $msg->delete();
        $process_page = Session::get('process_page');
        if(empty($process_page)){
            $process_page = "1";
        }
        return [
            'process_page' => $process_page,
        ];

    }


    public function delete(TaskAssignment $task_assignment)
    {
        $task_assignment->is_deleted = 1;
        $task_assignment->update();

        $task  = $task_assignment->task;

        $all_deleted_flag = true;
        foreach ($task->task_assignments as $task_asm){
            if(!$task_asm->is_deleted){
                $all_deleted_flag = false;
                break;
            }
        }

        if($all_deleted_flag){
            $task->is_deleted = 1;
            $task->update();
        }
        $prev_url = redirect()->back()->getTargetUrl();
        if (strpos($prev_url, 'edit') !== false) {
            return redirect()->route('tasks.index');
        }
        else{
            return redirect()->back();
        }

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
//        dd($request);
        $this->validate($request, [
            'user_ids' => 'required',
            'content' => 'required',
            'topic' => 'required',
        ]);

        $task = Task::create([
            'content'=>$request->input('content'),
            'topic'=>$request->input('topic'),
            'create_date' => now(),
            'update_date' => now(),
        ]);

        foreach($request->input('user_ids') as $user_id){
            $task_asm = TaskAssignment::create([
                'user_id' => $user_id,
                'task_id' => $task->id,
                'create_date' => now(),
                'update_date' => now(),
                'status' => 0,
            ]);
        }

        return redirect()->route('tasks.index');


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
    public function edit(Request $request, Task $task)
    {
        //
        $data = [
            'source_html' => $request->input('source_html'),
            'task' => $task,
            'users' => User::all(),
        ];
        return view('tasks.rootedit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
        $this->validate($request,[
            'user_ids' => 'required',
            'content' => 'required',
            'topic' => 'required',
        ]);
        $task->topic = $request->input('topic');
        $task->content = $request->input('content');
        $task->update_date = now();
        $task->update();


        $user_ids = $request->input('user_ids');
        $task_asms = $task->task_assignments;
        $request_user_count = 0;
        $origin_user_count = 0;

        if ($user_ids) {
            $request_user_count = count($user_ids);
        }
        if ($task_asms) {
            $origin_user_count = count($task_asms);

        }


        if ($request_user_count == $origin_user_count) {
            for ($i = 0; $i < $request_user_count; $i++) {
                $task_asm = $task_asms[$i];
                $task_asm->user_id = $user_ids[$i];
                $task_asm->update_date = now();
                $task_asm->update();
            }

        }
        elseif ($request_user_count > $origin_user_count) {
//            need to add
            for ($i = 0; $i < $origin_user_count; $i++) {
                $task_asm = $task_asms[$i];
                $task_asm->user_id = $user_ids[$i];
                $task_asm->update_date = now();

                $task_asm->update();
            }
            for ($i = $origin_user_count; $i < $request_user_count; $i++) {
                TaskAssignment::create([
                    'status' => 0,
                    'task_id' => $task->id,
                    'user_id' => $user_ids[$i],
                    'create_date' => now(),
                    'update_date' => now(),
                ]);
            }
        } else {
//            need to delete
            for ($i = 0; $i < $request_user_count; $i++) {
                $task_asm = $task_asms[$i];
                $task_asm->user_id = $user_ids[$i];
                $task_asm->update_date = now();

                $task_asm->update();
            }
            for ($i = $request_user_count; $i < $origin_user_count; $i++) {
                $task_asm = $task_asms[$i];
                $task_asm->delete();
            }
        }

        return Redirect::to($request->input('source_html'));

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
