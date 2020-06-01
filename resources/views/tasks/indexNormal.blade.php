@extends('layouts.master')

@section('title', '任務列表')

@section('content')
    <meta id="csrf_token" name="csrf_token" content="{{ csrf_token() }}"/>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                任務列表
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('tasks.index')}}"><i class="fa fa-shopping-bag"></i> 任務清單</a></li>
                <li class="active">任務列表</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            {{--            page--}}

            <script>
                function process_page(page) {
                    $.ajax({
                        type: "get",
                        url: "{{route('tasks.setPageSession')}}",
                        data: {
                            process_page: page
                        },
                        success: function (msg) {
                            console.log(msg);
                        }
                    });
                    $.ajax({
                        type: "get",
                        url: "{{route('tasks.getProcessPage')}}",
                        data: {
                            page: page
                        },
                        success: function (msg) {
                            if (msg) {
                                // console.log(msg);
                                const myNode = document.getElementById("dynamic_process_page");
                                myNode.innerHTML = '';
                                $("#dynamic_process_page").append(msg);
                            }
                        }
                    })
                }

                function check_page(page) {
                    $.ajax({
                        type: "get",
                        url: "{{route('tasks.setPageSession')}}",
                        data: {
                            check_page: page
                        },
                        success: function (msg) {
                            console.log(msg);
                        }
                    });
                    $.ajax({
                        type: "get",
                        url: "{{route('tasks.getCheckPage')}}",
                        data: {
                            page: page
                        },
                        success: function (msg) {
                            if (msg) {
                                const myNode = document.getElementById("dynamic_check_page");
                                myNode.innerHTML = '';
                                $("#dynamic_check_page").append(msg);
                            }
                        }
                    })
                }

                function done_page(page) {
                    $.ajax({
                        type: "get",
                        url: "{{route('tasks.setPageSession')}}",
                        data: {
                            done_page: page
                        },
                        success: function (msg) {
                            console.log(msg);
                        }
                    });
                    $.ajax({
                        type: "get",
                        url: "{{route('tasks.getDonePage')}}",
                        data: {
                            page: page
                        },
                        success: function (msg) {
                            if (msg) {
                                const myNode = document.getElementById("dynamic_done_page");
                                myNode.innerHTML = '';
                                $("#dynamic_done_page").append(msg);
                            }
                        }
                    })
                }

            </script>


            <div class="tabs">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <!-- /.box-header -->
                            <div class="box-body">

                                <div id="Customer">
                                    <h4 class="text-center">
                                        <label style="font-size: medium;color: darkred">未完成</label>
                                    </h4>
                                </div>
                                <script>
                                    function taskComplete(button) {
                                        //    button will have task id
                                        var task_id = button.id;
                                        console.log(task_id);
                                        var text_box_id = task_id + "_textbox";
                                        var text_box = document.getElementById(text_box_id);
                                        var msg = text_box.value;
                                        $.ajax({
                                            async: false,
                                            type: "POST",
                                            url: '{{route('tasks.taskComplete')}}',
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                            },
                                            data: {
                                                task_id: task_id,
                                                msg: msg,
                                            },
                                            success: function (data) {
                                                // console.log(data.process_page)
                                                process_page(data.process_page);
                                                check_page(data.check_page);
                                                //    call below too

                                            },
                                            error: function () {
                                                alert('伺服器出了點問題，稍後再重試');
                                                return;
                                            }
                                        });
                                    }

                                    function taskBackToProcess(task_asm_id) {
                                        $.ajax({
                                            async: false,
                                            type: "POST",
                                            url: '{{route('tasks.taskBackToProcess')}}',
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                            },
                                            data: {
                                                task_id: task_asm_id,
                                            },
                                            success: function (data) {
                                                process_page(data.process_page);
                                                check_page(data.check_page);

                                            },
                                            error: function () {
                                                alert('伺服器出了點問題，稍後再重試');
                                                return;
                                            }
                                        });

                                    }
                                </script>

                                <div id="dynamic_process_page">

                                    <table class="table table-striped" style="width: 100%">
                                        <thead style="background-color: lightgray">
                                        <tr class="text-center">
                                            <th class="text-center" style="width: 10%;">主題</th>
                                            <th class="text-center" style="width: 10%;">任務內容</th>
                                            <th class="text-center" style="width: 20%;">訊息</th>
                                            <th class="text-center" style="width: 20%;">回覆／完成任務</th>

                                        </tr>
                                        </thead>
                                        @foreach($processAsms as $asm)
                                            <tr>
                                                <td class="text-center">
                                                    {{$asm->task->topic}}
                                                </td>
                                                <td class="text-center">
                                                    {{$asm->task->content}}
                                                </td>
                                                <td style="text-align: center">
                                                    <ul class="fa-ul" style="display: inline-block">
                                                        @foreach($asm->task_reply_msgs()->orderBy('create_date','DESC')->get() as $msg)
                                                            <li>
                                                                @if($msg->user_id == Auth::user()->id)
                                                                    <i class="fa fa-li fa-mail-forward"
                                                                       style="color: green"></i>
                                                                @else
                                                                    <i class="fa fa-li fa-mail-reply"
                                                                       style="color: red"></i>
                                                                @endif
                                                                {{--                                                            {{dump($msg->update_date)}}--}}
                                                                {{$msg->text}} &nbsp &nbsp<span
                                                                    style="float: right">{{date("Y/m/d H:i", strtotime($msg->update_date))}}</span>

                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td style="text-align: center">
                                                    <div class="input-group" style="display: inline-block">
                                                        <textarea class="form-control"
                                                                  id="{{$asm->id}}_textbox"></textarea>
                                                        <button class="form-control" id="{{$asm->id}}"
                                                                onclick="taskComplete(this)"
                                                                style="background-color: forestgreen;color: white">完成
                                                        </button>
                                                    </div>


                                                </td>

                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="page">
                                        <!-------分页---------->
                                        @if($process_count > $process_rev)
                                            <ul class="pagination">
                                                @if($process_page !=1)
                                                    <li>
                                                        <a href="javascript:void(0)"
                                                           onclick="process_page({{$process_prev}})"><<</a>
                                                    </li>
                                                @endif
                                                @php($process_flag = true)
                                                @foreach($process_pp as $k=>$v)
                                                    {{--                                                當前page--}}
                                                    @if($v == $process_page)
                                                        <li class="active"><span>{{$v}}</span></li>
                                                        {{--                                                    超過範圍之外page--}}
                                                    @elseif(abs($v-$process_page)>=3 && $v<$process_page)
                                                        @if($v==1)
                                                            <li>
                                                                <a href="javascript:void(0)"
                                                                   onclick="process_page({{$v}})">{{$v}}</a>
                                                            </li>
                                                        @else
                                                            @if($process_flag)
                                                                <li><span>...</span></li>
                                                                @php($process_flag = false)
                                                            @endif
                                                        @endif

                                                    @elseif(abs($v-$process_page)>=3 && $v>$process_page)
                                                        @if($v==count($process_pp))
                                                            <li>
                                                                <a href="javascript:void(0)"
                                                                   onclick="process_page({{$v}})">{{$v}}</a>
                                                            </li>
                                                        @else
                                                            @if($process_flag)
                                                                <li><span>...</span></li>
                                                                @php($process_flag = false)
                                                            @endif
                                                        @endif

                                                    @else
                                                        @php($process_flag = true)
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                               onclick="process_page({{$v}})">{{$v}}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                                <li>
                                                    <a href="javascript:void(0)"
                                                       onclick="process_page({{$process_next}})">>></a>
                                                </li>
                                            </ul>
                                    @endif
                                    <!-------分页---------->
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--                            Order Note--}}
            <div class="tabs">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">

                            <div class="box-body">
                                <h4 class="text-center">
                                    <label style="font-size: medium;color: darkorange">已處理，待確認</label>
                                </h4>


                                <div id="dynamic_check_page">
                                    <table class="table table-striped" style="width: 100%">
                                        <thead style="background-color: lightgray">
                                        <tr class="text-center">
                                            <th class="text-center" style="width: 10%;">主題</th>
                                            <th class="text-center" style="width: 10%;">任務內容</th>
                                            <th class="text-center" style="width: 20%;">訊息</th>
                                            <th class="text-center" style="width: 20%;">其他</th>

                                        </tr>
                                        </thead>
                                        @foreach($needToCheckAsms as $asm)
                                            <tr>
                                                <td class="text-center">
                                                    {{$asm->task->topic}}
                                                </td>
                                                <td class="text-center">
                                                    {{$asm->task->content}}
                                                </td>
                                                <td style="text-align: center">
                                                    <ul class="fa-ul" style="display: inline-block">
                                                        @foreach($asm->task_reply_msgs()->orderBy('create_date','DESC')->get() as $msg)
                                                            <li>
                                                                @if($msg->user_id == Auth::user()->id)
                                                                    <i class="fa fa-li fa-mail-forward"
                                                                       style="color: green"></i>
                                                                @else
                                                                    <i class="fa fa-li fa-mail-reply"
                                                                       style="color: red"></i>
                                                                @endif
                                                                {{--                                                            {{dump($msg->update_date)}}--}}
                                                                {{$msg->text}} &nbsp &nbsp<span
                                                                    style="float: right">{{date("Y/m/d H:i", strtotime($msg->update_date))}}</span>

                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td style="text-align: center;vertical-align: middle">
                                                    <a onclick="taskBackToProcess({{$asm->id}})"
                                                       class="btn btn-sm btn-warning">
                                                        退回未處理
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="page">
                                        <!-------分页---------->
                                        @if($check_count > $check_rev)
                                            <ul class="pagination">
                                                @if($check_page !=1)
                                                    <li>
                                                        <a href="javascript:void(0)"
                                                           onclick="check_page({{$check_prev}})"><<</a>
                                                    </li>
                                                @endif
                                                @php($check_flag = true)
                                                @foreach($check_pp as $k=>$v)
                                                    {{--                                                當前page--}}
                                                    @if($v == $check_page)
                                                        <li class="active"><span>{{$v}}</span></li>
                                                        {{--                                                    超過範圍之外page--}}
                                                    @elseif(abs($v-$check_page)>=3 && $v<$check_page)
                                                        @if($v==1)
                                                            <li>
                                                                <a href="javascript:void(0)"
                                                                   onclick="check_page({{$v}})">{{$v}}</a>
                                                            </li>
                                                        @else
                                                            @if($check_flag)
                                                                <li><span>...</span></li>
                                                                @php($check_flag = false)
                                                            @endif
                                                        @endif

                                                    @elseif(abs($v-$check_page)>=3 && $v>$check_page)
                                                        @if($v==count($check_pp))
                                                            <li>
                                                                <a href="javascript:void(0)"
                                                                   onclick="check_page({{$v}})">{{$v}}</a>
                                                            </li>
                                                        @else
                                                            @if($check_flag)
                                                                <li><span>...</span></li>
                                                                @php($check_flag = false)
                                                            @endif
                                                        @endif

                                                    @else
                                                        @php($check_flag = true)
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                               onclick="check_page({{$v}})">{{$v}}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                                <li>
                                                    <a href="javascript:void(0)"
                                                       onclick="check_page({{$check_next}})">>></a>
                                                </li>
                                            </ul>
                                    @endif
                                    <!-------分页---------->
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                {{--                            Order Items--}}

                <div class="tabs">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">


                                <div class="box-body">
                                    <div id="Development_Record">
                                        <h4 class="text-center">
                                            <label style="font-size: medium;color: green">完成</label>
                                        </h4>
                                    </div>
                                    <div id="dynamic_done_page">
                                        <table class="table table-striped" style="width: 100%">
                                            <thead style="background-color: lightgray">
                                            <tr class="text-center">
                                                <th class="text-center" style="width: 10%;">主題</th>
                                                <th class="text-center" style="width: 10%;">任務內容</th>
                                                <th class="text-center" style="width: 20%;">訊息</th>

                                            </tr>
                                            </thead>
                                            @foreach($doneAsms as $asm)
                                                <tr>
                                                    <td class="text-center">
                                                        {{$asm->task->topic}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$asm->task->content}}
                                                    </td>
                                                    <td style="text-align: center">
                                                        <ul class="fa-ul" style="display: inline-block">
                                                            @foreach($asm->task_reply_msgs()->orderBy('create_date','DESC')->get() as $msg)
                                                                <li>
                                                                    @if($msg->user_id == Auth::user()->id)
                                                                        <i class="fa fa-li fa-mail-forward"
                                                                           style="color: green"></i>
                                                                    @else
                                                                        <i class="fa fa-li fa-mail-reply"
                                                                           style="color: red"></i>
                                                                    @endif
                                                                    {{$msg->text}} &nbsp &nbsp<span
                                                                        style="float: right">{{date("Y/m/d H:i", strtotime($msg->update_date))}}</span>

                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                        <div class="page">
                                            <!-------分页---------->
                                            @if($done_count > $done_rev)
                                                <ul class="pagination">
                                                    @if($done_page !=1)
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                               onclick="done_page({{$done_prev}})"><<</a>
                                                        </li>
                                                    @endif
                                                    @php($done_flag = true)
                                                    @foreach($done_pp as $k=>$v)
                                                        {{--                                                當前page--}}
                                                        @if($v == $done_page)
                                                            <li class="active"><span>{{$v}}</span></li>
                                                            {{--                                                    超過範圍之外page--}}
                                                        @elseif(abs($v-$done_page)>=3 && $v<$done_page)
                                                            @if($v==1)
                                                                <li>
                                                                    <a href="javascript:void(0)"
                                                                       onclick="done_page({{$v}})">{{$v}}</a>
                                                                </li>
                                                            @else
                                                                @if($done_flag)
                                                                    <li><span>...</span></li>
                                                                    @php($done_flag = false)
                                                                @endif
                                                            @endif

                                                        @elseif(abs($v-$done_page)>=3 && $v>$done_page)
                                                            @if($v==count($done_pp))
                                                                <li>
                                                                    <a href="javascript:void(0)"
                                                                       onclick="done_page({{$v}})">{{$v}}</a>
                                                                </li>
                                                            @else
                                                                @if($done_flag)
                                                                    <li><span>...</span></li>
                                                                    @php($done_flag = false)
                                                                @endif
                                                            @endif

                                                        @else
                                                            @php($done_flag = true)
                                                            <li>
                                                                <a href="javascript:void(0)"
                                                                   onclick="done_page({{$v}})">{{$v}}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                    <li>
                                                        <a href="javascript:void(0)"
                                                           onclick="done_page({{$done_next}})">>></a>
                                                    </li>
                                                </ul>
                                        @endif
                                        <!-------分页---------->
                                        </div>


                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
