@extends('layouts.master')

@section('title', '任務列表')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <a href="{{route('tasks.index')}}">任務列表</a>
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><i class="fa fa-shopping-bag"></i> 任務清單</li>
                <li class="active">任務列表</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="row">
                                <form name="filter_form" action="{{route('tasks.index')}}" method="get">
                                    <div class="col-md-2">
                                        <label>業務</label>
                                        <select name="user_filter" class="form-control form-control-sm">
                                            <option value="-1" @if($user_filter==-1) selected @endif>All</option>
                                            @foreach($users as $user)
                                                @if(!$user->is_left && $user->level!=2 && $user->id>1)
                                                    <option @if($user->id==$user_filter) selected
                                                            @endif value="{{$user->id}}">{{$user->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>狀態</label>
                                        <select name="status_filter" class="form-control form-control-sm">
                                            <option value="-1" @if(-1==$status_filter) selected @endif>All
                                            </option>
                                            @foreach($task_status_names as $status_name)
                                                <option @if($loop->index==$status_filter) selected
                                                        @endif value="{{$loop->index}}">{{$status_name}}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="form-control">篩選</button>
                                    </div>

                                </form>
                                <div class="col-md-3">
                                    <label>搜尋</label><br>
                                    <!-- search form (Optional) -->
                                    <form roe="form" action="{{route('tasks.index')}}" method="get">
                                        <div class="form-inline">
                                            <select name="search_type" class="form-group form-control"
                                                    style="width: 100%;">
                                                <option value="1" @if(request()->get('search_type')==1) selected @endif>
                                                    主題
                                                </option>
                                                <option value="2" @if(request()->get('search_type')==2) selected @endif>
                                                    內容
                                                </option>
                                            </select>
                                            <div class="inline">
                                                <input type="text" name="search_info" class="form-control"
                                                       style="width: 80%"
                                                       placeholder="Search..."
                                                       value="@if(request()->get('search_info')) {{request()->get('search_info')}} @endif">
                                                <button type="submit" id="search-btn" style="cursor: pointer"
                                                        style="width: 20%"
                                                        class="btn btn-flat"><i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input hidden name="user_filter" value="{{$user_filter}}">
                                        <input hidden name="status_filter" value="{{$status_filter}}">
                                    </form>
                                    <!-- /.search form -->


                                </div>

                                <div class="col-md-1">
                                    <label>特殊功能</label><br>
                                    <a class="btn btn-success btn-sm" href="{{route('tasks.create')}}">新增任務</a>
                                </div>

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body ">

                            <script>
                                function taskChecked(button) {
                                    //    button will have task id
                                    var task_id = button.id.replace('_checked', '');
                                    console.log(task_id);
                                    var text_box_id = task_id + "_textbox";
                                    var text_box = document.getElementById(text_box_id);
                                    var msg = text_box.value;
                                    $.ajax({
                                        async: false,
                                        type: "POST",
                                        url: '{{route('tasks.taskChecked')}}',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                        },
                                        data: {
                                            task_id: task_id,
                                            msg: msg,
                                        },
                                        success: function (data) {
                                            console.log(data);
                                            // var data = JSON.parse(data);
                                            window.location.reload();

                                        },
                                        error: function () {
                                            alert('伺服器出了點問題，稍後再重試');
                                            return;
                                        }
                                    });
                                }

                                function taskBack(button) {
                                    //    button will have task id
                                    var task_id = button.id.replace('_back', '');
                                    console.log(task_id);
                                    var text_box_id = task_id + "_textbox";
                                    var text_box = document.getElementById(text_box_id);
                                    var msg = text_box.value;
                                    $.ajax({
                                        async: false,
                                        type: "POST",
                                        url: '{{route('tasks.taskBack')}}',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                        },
                                        data: {
                                            task_id: task_id,
                                            msg: msg,
                                        },
                                        success: function (data) {
                                            console.log(data);
                                            // var data = JSON.parse(data);
                                            window.location.reload();

                                        },
                                        error: function () {
                                            alert('伺服器出了點問題，稍後再重試');
                                            return;
                                        }
                                    });
                                }

                                function task_asm_edit(task_id) {
                                    console.log(encodeURIComponent(window.location.href));
                                    window.location.href = '/tasks/' + task_id + '/edit' + '?source_html=' + encodeURIComponent(window.location.href);
                                }

                            </script>


                            <table class="table table-bordered table-hover" style="width: 100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th rowspan="2" class="text-center align-middle"
                                        style="width:10%;vertical-align: middle">主題
                                    </th>
                                    <th rowspan="2" class="text-center" style="width:20%;vertical-align: middle">任務內容
                                    </th>
                                    <th colspan="5" class="text-center" style="width:70%;vertical-align: middle">MORE
                                    </th>
                                </tr>
                                <tr>
                                    <td class="text-center" style="width: 5%">業務</td>
                                    <td class="text-center" style="width: 5%">狀態</td>
                                    <td class="text-center" style="width: 20%">訊息</td>
                                    <td class="text-center" style="width: 30%">回覆</td>
                                    <td class="text-center" style="width: 10%">功能</td>


                                </tr>

                                </thead>

                                @foreach($tasks as $task)
                                    @php($edit_flag = true)
                                    @php($asms_count = 0)
                                    @foreach($task->task_assignments as $task_asm)
                                        @php($flag = true)
                                        @if($task_asm->is_deleted)
                                            @php($flag = false)
                                        @endif
                                        @if($user_filter != -1)
                                            @if($user_filter != $task_asm->user_id)
                                                @php($flag = false)
                                            @endif
                                        @endif
                                        @if($status_filter != -1)
                                            @if($status_filter != $task_asm->status)
                                                @php($flag = false)
                                            @endif
                                        @endif
                                        @if($flag)
                                            @php($asms_count+=1)
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td style="vertical-align: middle"
                                            rowspan="{{$asms_count+1}}"
                                            class="text-left">{{$task->topic}}</td>
                                        <td style="vertical-align: middle"
                                            rowspan="{{$asms_count+1}}"
                                            class="text-left">{{$task->content}} </td>
                                    </tr>
                                    <tr>
                                        @foreach($task->task_assignments as $task_assignment)
                                            @if($task_assignment->is_deleted)
                                                @continue
                                            @endif
                                            @if($user_filter != -1)
                                                @if($task_assignment->user_id != $user_filter)
                                                    @continue
                                                @endif
                                            @endif
                                            @if($status_filter != -1)
                                                @if($task_assignment->status != $status_filter)
                                                    @continue
                                                @endif
                                            @endif
                                            {{--                                                                                    <tr>--}}
                                            <td class="align-middle text-center "
                                                style="vertical-align: middle">{{$task_assignment->user->name}}</td>
                                            @switch($task_assignment->status)
                                                @case(0)
                                                @php($css='label label-danger')
                                                @break
                                                @case(1)
                                                @php($css='label label-warning')
                                                @break
                                                @case(2)
                                                @php($css='label label-success')
                                                @break
                                                @default
                                                @break
                                            @endswitch
                                            <td class="align-middle text-center "
                                                style="vertical-align: middle"><label
                                                    class="label{{$css}}"
                                                    style="min-width:100px;display: inline-block;color: #0f0f0f">{{ $task_status_names[$task_assignment->status] }}</label>
                                                {{--                                                <i class="fa fa-comment-o"></i>&nbsp{{count($task_assignment->task_reply_msgs)}}--}}
                                            </td>
                                            <td >
                                                <ul class="fa-ul" style="display: inline-block">
                                                    @foreach($task_assignment->task_reply_msgs as $msg)
                                                        <li>
                                                            @if($msg->user_id == Auth::user()->id)
                                                                <i class="fa fa-li fa-mail-forward"
                                                                   style="color: green"></i>
                                                            @else
                                                                <i class="fa fa-li fa-mail-reply"
                                                                   style="color: red"></i>
                                                            @endif
                                                                {{$msg->text}} &nbsp &nbsp<span style="float: right">{{date("Y/m/d H:i", strtotime($msg->update_date))}}</span>


                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td style="text-align: center">
                                                <div class="input-group" style="display: inline-block">

                                                    @if($task_assignment->status == 1)
                                                        <textarea class="form-control"
                                                                  id="{{$task_assignment->id}}_textbox"></textarea>
                                                        <div style="display: inline">
                                                            <div style="width: 50%;float: left">
                                                                <button class="form-control"
                                                                        id="{{$task_assignment->id}}_back"
                                                                        onclick="taskBack(this)"
                                                                        style="background-color: #bb2124;color: white">
                                                                    退回
                                                                </button>
                                                            </div>
                                                            <div style="width: 50%;float: right">

                                                                <button class="form-control"
                                                                        id="{{$task_assignment->id}}_checked"
                                                                        onclick="taskChecked(this)"
                                                                        style="background-color: forestgreen;color: white">
                                                                    完成
                                                                </button>
                                                            </div>
                                                        </div>



                                                    @endif
                                                    <form
                                                        action="{{ route('tasks.delete', $task_assignment->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <button type="submit" class=" form-control"
                                                                style="background-color: #bb2124;color: white;vertical-align: middle;"
                                                                onclick="return confirm('確定是否刪除')">刪除
                                                        </button>
                                                    </form>


                                                </div>
                                            </td>
                                            @if($edit_flag)
                                                <td rowspan="{{$asms_count}}"
                                                    style="vertical-align: middle;horiz-align: center"
                                                    class="text-center">
                                                    <button onclick="task_asm_edit({{$task->id}})"
                                                            class="btn btn-sm btn-primary">
                                                        編輯
                                                    </button>
                                                </td>
                                                @php($edit_flag = false)
                                            @endif
                                            {{--                                        </tr>--}}
                                            @if($loop->last)
                                    </tr>
                                    @else
                                    </tr>
                                    <tr>
                                    @endif
                                @endforeach
                                {{--                                        </td>--}}

                                @endforeach


                            </table>
                            <div class="box-footer clearfix">
                                {{ $tasks->appends(request()->input())->links() }}

                            </div>
                        </div>

                    </div>


                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        {{--    </div>--}}
        <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
