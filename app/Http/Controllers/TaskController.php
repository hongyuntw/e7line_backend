<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public static $task_status_names = ['未處理','已處理/待確認','已完成'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        if(Auth::user()->level==2){
            $query = Task::query();
            $query->orderBy('tasks.create_date','DESC');
            $tasks = $query->paginate(15);

            $data = [
                'tasks' => $tasks,
                'task_status_names' => self::$task_status_names,

            ];

            return view('tasks.index',$data);

        }
        else{

            $query = TaskAssignment::query();
            $query->where('user_id','=',Auth::user()->id);
            $query->orderBy('task_assignments.create_date','DESC');
            $query_2 = $query;
            $query_1 = $query;

            $doneTaskAssignments = $query_2->where('status','=',2)->get();
            $checkTaskAssignments = $query_1->where('status','=',1)->get();
            $needToCheckTaskAssignments = $query->where('status','=',0)->get();
            dump($doneTaskAssignments);
            dump($checkTaskAssignments);
            dd($needToCheckTaskAssignments);
            $tasks = $query->paginate(15);

            $data = [


            ];



            return view('tasks.indexNormal');
        }

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


    public function delete()
    {

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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
