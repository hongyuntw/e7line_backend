@extends('layouts.master')

@section('title', '任務列表')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <a href="{{route('orders.index')}}">任務列表</a>
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
                                {{--                                <form name="filter_form" action="{{route('orders.index')}}" method="get">--}}
                                {{--                                    <div class="col-md-2">--}}
                                {{--                                        <label>業務</label>--}}
                                {{--                                        <select name="user_filter" class="form-control form-control-sm">--}}
                                {{--                                            <option value="-1" @if($user_filter==-1) selected @endif>All</option>--}}
                                {{--                                            @foreach($users as $user)--}}
                                {{--                                                <option @if($user->id==$user_filter) selected--}}
                                {{--                                                        @endif value="{{$user->id}}">{{$user->name}}</option>--}}
                                {{--                                            @endforeach--}}
                                {{--                                        </select>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="col-md-2">--}}
                                {{--                                        <label>訂單狀態</label>--}}
                                {{--                                        <select name="status_filter" class="form-control form-control-sm">--}}
                                {{--                                            <option value="-1" @if(-1==$status_filter) selected--}}
                                {{--                                                @endif>All--}}
                                {{--                                            </option>--}}
                                {{--                                            @foreach($order_status_names as $status_name)--}}
                                {{--                                                <option @if($loop->index==$status_filter) selected--}}
                                {{--                                                        @endif value="{{$loop->index}}">{{$status_name}}</option>--}}
                                {{--                                            @endforeach--}}
                                {{--                                        </select>--}}
                                {{--                                        <label>拋單狀態</label>--}}
                                {{--                                        <select name="code_filter" class="form-control form-control-sm">--}}
                                {{--                                            <option value="-1" @if(-1==$code_filter) selected @endif>All</option>--}}
                                {{--                                            <option value="0" @if(0==$code_filter) selected @endif>未拋單</option>--}}
                                {{--                                            <option value="1" @if(1==$code_filter) selected @endif>已拋單</option>--}}
                                {{--                                        </select>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="col-md-2">--}}
                                {{--                                        <label>日期篩選</label>--}}
                                {{--                                        <input type="date" class="form-control" name="date_from"--}}
                                {{--                                               value="@if($date_from != null){{($date_from)}}@endif">--}}
                                {{--                                        至--}}
                                {{--                                        <input type="date" class="form-control" name="date_to"--}}
                                {{--                                               value="@if($date_to != null){{$date_to}}@endif">--}}

                                {{--                                    </div>--}}

                                {{--                                    <div class="col-md-2">--}}
                                {{--                                        <label>排序方式</label>--}}
                                {{--                                        <select name="sortBy" class="form-control form-control-sm">--}}
                                {{--                                            @foreach(['create_date','receive_date'] as $col)--}}
                                {{--                                                <option @if($sortBy == $col) selected--}}
                                {{--                                                        @endif value="{{$col}}">{{$sortBy_text[$loop->index]}}</option>--}}
                                {{--                                            @endforeach--}}
                                {{--                                        </select>--}}
                                {{--                                        <br>--}}
                                {{--                                        <button type="submit" class=" btn btn-sm bg-blue" style="width: 100%">篩選--}}
                                {{--                                        </button>--}}
                                {{--                                    </div>--}}

                                {{--                                </form>--}}
                                {{--                                <div class="col-md-3">--}}
                                {{--                                    <label>搜尋</label><br>--}}
                                {{--                                    <!-- search form (Optional) -->--}}
                                {{--                                    <form roe="form" action="{{route('orders.index')}}" method="get">--}}
                                {{--                                        <div class="form-inline">--}}
                                {{--                                            <select name="search_type" class="form-group form-control"--}}
                                {{--                                                    style="width: 100%;">--}}
                                {{--                                                <option value="1" @if(request()->get('search_type')==1) selected @endif>--}}
                                {{--                                                    訂單編號--}}
                                {{--                                                </option>--}}
                                {{--                                                <option value="2" @if(request()->get('search_type')==2) selected @endif>--}}
                                {{--                                                    客戶名稱--}}
                                {{--                                                </option>--}}

                                {{--                                                <option value="3" @if(request()->get('search_type')==3) selected @endif>--}}
                                {{--                                                    統編--}}
                                {{--                                                </option>--}}
                                {{--                                                <option value="4" @if(request()->get('search_type')==4) selected @endif>--}}
                                {{--                                                    訂購人名稱--}}
                                {{--                                                </option>--}}
                                {{--                                            </select>--}}
                                {{--                                            <div class="inline">--}}
                                {{--                                                <input type="text" name="search_info" class="form-control"--}}
                                {{--                                                       style="width: 80%"--}}
                                {{--                                                       placeholder="Search..."--}}
                                {{--                                                       value="@if(request()->get('search_info')) {{request()->get('search_info')}} @endif">--}}
                                {{--                                                <button type="submit" id="search-btn" style="cursor: pointer"--}}
                                {{--                                                        style="width: 20%"--}}
                                {{--                                                        class="btn btn-flat"><i class="fa fa-search"></i>--}}
                                {{--                                                </button>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                        <input hidden name="code_filter" value="{{$code_filter}}">--}}
                                {{--                                        <input hidden name="user_filter" value="{{$user_filter}}">--}}
                                {{--                                        <input hidden name="status_filter" value="{{$status_filter}}">--}}
                                {{--                                        <input hidden name="date_from" value="{{$date_from}}">--}}
                                {{--                                        <input hidden name="date_to" value="{{$date_to}}">--}}
                                {{--                                        <input hidden name="sortBy" value="{{$sortBy}}">--}}


                                {{--                                    </form>--}}
                                {{--                                    <!-- /.search form -->--}}


                                {{--                                </div>--}}

                                {{--                                <div class="col-md-1">--}}
                                {{--                                    <label>特殊功能</label><br>--}}
                                {{--                                    <a class="btn btn-success btn-sm" href="{{route('orders.create')}}">新增訂單</a>--}}
                                {{--                                </div>--}}

                            </div>


                        </div>


                        <!-- /.box-header -->
                        <div class="box-body ">

                            <table class="table table-bordered table-hover" width="100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th style="width:3%"></th>
                                    <th class="text-center" style="width:10%">主題</th>
                                    <th class="text-center" style="width:10%">Status</th>
                                    <th class="text-center" style="width:80%">任務內容</th>

                                    @if(Auth::user()->level==2)
                                        <th class="text-center" style="width:20%">回覆內容</th>
                                        <th class="text-center" style="width:20%">指派業務</th>
                                    @endif
                                </tr>
                                </thead>

                                @foreach($tasks as $task)
                                    <tr>
                                        <td></td>
                                        <td class="text-left">{{$task->topic}}</td>
                                        @switch($task->status)
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
                                        <td class="align-middle " style="vertical-align: middle"><label
                                                class="label{{$css}}"
                                                style="min-width:60px;display: inline-block">{{ $task_status_names[$task->status] }}</label>
                                        <td class="text-left">{{$task->content}}</td>
                                        <td class="text-left">{{$task->reply}}</td>
                                        @if(Auth::user()->level==2)
                                            <td>{{$task->reply}}</td>
                                            <td>
                                                {{$task->users}}
                                                @foreach($task->users as $user)
                                                    <li>{{$user->name}}</li>
                                                @endforeach
                                            </td>
                                        @endif


                                    </tr>

                                @endforeach


                            </table>
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
