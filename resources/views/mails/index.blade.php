@extends('layouts.master')

@section('title', '郵件列表')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <a href="{{route('mails.index')}}">郵件列表</a>
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 郵件列表</a></li>
                {{--                <li class="active">客戶列表</li>--}}
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
                                <form name="filter_form" action="{{route('mails.index')}}" method="get">
{{--                                    <div class="col-md-2 col-3">--}}
{{--                                        <label>福委篩選</label>--}}
{{--                                        <select name="user_filter" class="form-control form-control-sm"--}}
{{--                                                value="{{$user_filter}}">--}}
{{--                                            <option value="0" @if($user_filter==0) selected @endif>全部客戶</option>--}}
{{--                                            <option value="1" @if($user_filter==1) selected @endif>我的客戶</option>--}}
{{--                                            <option value="2" @if($user_filter==2) selected @endif>未指定業務</option>--}}

{{--                                        </select>--}}
{{--                                    </div>--}}
                                    <div class="col-md-2 col-3">
                                        <label>在職狀態篩選</label>
                                        <select name="is_left"
                                                class="form-control form-control-sm">
                                            @foreach(['-1','0','1'] as $col)
                                                <option @if($col==$is_left) selected
                                                        @endif value="{{$col}}">{{$status_text[$loop->index]}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <label>收信狀態篩選</label>
                                        <select  name="want_receive_mail"
                                                class="form-control form-control-sm">
                                            @foreach(['-1','1','0'] as $col)
                                                <option @if($col==$want_receive_mail) selected
                                                        @endif value="{{$col}}">@if($col==-1)---@elseif($col==1)是@else否@endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <label>排序方式</label>
                                        <select multiple name="sortBy[]" class="form-control form-control-sm">
                                            @foreach(['create_date','name','customer_name','is_left','area','want_receive_mail'] as $col)
                                                <option @if(is_array($sortBy))
                                                        @if(in_array($col, $sortBy))
                                                        selected
                                                        @endif
                                                        @endif value="{{$col}}">{{$sortBy_text[$loop->index]}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-3">
                                        <label>篩選按鈕</label><br>
                                        <button type="submit" class="w-100 btn btn-sm bg-blue">Filter</button>
                                    </div>
                                </form>
                                <div class="col-md-3 col-3">
                                    <label>搜尋</label><br>
                                    <!-- search form (Optional) -->
                                    <form roe="form" action="{{route('mails.index')}}" method="get">
                                        <div class="form-inline">
                                            <select name="search_type" class="form-group form-control">
                                                <option value="1" @if(request()->get('search_type')==1) selected @endif>
                                                    姓名
                                                </option>
                                                <option value="2" @if(request()->get('search_type')==2) selected @endif>
                                                    公司(客戶)
                                                </option>
                                                <option value="3" @if(request()->get('search_type')==3) selected @endif>
                                                    地區
                                                </option>
                                                <option value="4" @if(request()->get('search_type')==4) selected @endif>
                                                    email
                                                </option>
                                            </select>
                                            <br>
                                            <div class="inline">
                                                <input type="text" name="search_info" class="form-control"
                                                       placeholder="Search..." value="@if(request()->get('search_info')) {{request()->get('search_info')}} @endif">
                                                <button type="submit" id="search-btn" style="cursor: pointer"
                                                        class="btn btn-flat"><i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input hidden name="is_left" value="{{$is_left}}">
                                        <input hidden name="want_receive_mail" value="{{$want_receive_mail}}">

                                        <select multiple name="sortBy[]" hidden>
                                            @if(request()->get('sortBy'))
                                                @foreach(request()->get('sortBy') as $col)
                                                    <option selected value="{{$col}}"></option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </form>
                                    <!-- /.search form -->

                                </div>
                                <div class="col-md-1 col-3">
                                    <label>特殊功能</label><br>
                                    <a class="btn btn-success btn-sm" href="{{route('mails.export',request()->input())}}">Export</a>
                                </div>
                            </div>


                        </div>

                        <!-- /.box-header -->
                        <div class="box-body ">

                            <table class="table table-bordered table-hover" width="100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th class="text-center" style="width:15%">姓名</th>
                                    <th class="text-center" style="width:30%">公司(客戶)</th>
                                    <th class="text-center" style="width:20%">縣市地區</th>
                                    <th class="text-center" style="width:20%">Email</th>
                                    <th class="text-center" style="width:8%">在職狀態</th>
                                    <th class="text-center" style="width:8%">收信意願</th>

                                </tr>
                                </thead>

                            @foreach ($concat_persons as $concat_person)

                                    <tr ondblclick="" class="text-center">
                                        <td class="text-left"><svg class="bi bi-person-fill" width="1em" height="1em"
                                                                   viewBox="0 0 16 16"
                                                                   fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                                   @if($concat_person->is_left==0) style="color:green" @endif>
                                                <path fill-rule="evenodd"
                                                      d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                            {{ $concat_person->name }}</td>
                                        <td class="text-left"><svg class="bi bi-briefcase-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M0 12.5A1.5 1.5 0 001.5 14h13a1.5 1.5 0 001.5-1.5V6.85L8.129 8.947a.5.5 0 01-.258 0L0 6.85v5.65z" clip-rule="evenodd"/>
                                                <path fill-rule="evenodd" d="M0 4.5A1.5 1.5 0 011.5 3h13A1.5 1.5 0 0116 4.5v1.384l-7.614 2.03a1.5 1.5 0 01-.772 0L0 5.884V4.5zm5-2A1.5 1.5 0 016.5 1h3A1.5 1.5 0 0111 2.5V3h-1v-.5a.5.5 0 00-.5-.5h-3a.5.5 0 00-.5.5V3H5v-.5z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $concat_person->customer_name  }}</td>
                                        <td>{{$concat_person->city}}{{$concat_person->area}}</td>
                                        <td class="text-left">{{ $concat_person->email  }}</td>

                                        @if ($concat_person->is_left==0)
                                            @php($css='label label-primary')
                                        @elseif($concat_person->is_left==1)
                                            @php($css='label label-warning')
                                        @endif
                                        <td>@if($concat_person->is_left)<span
                                                class="glyphicon glyphicon-remove"></span>@else<span
                                                class="glyphicon glyphicon-ok"></span>@endif
                                        </td>
                                        <td>@if($concat_person->want_receive_mail)<span
                                                class="glyphicon glyphicon-ok"></span>@else<span
                                                class="glyphicon glyphicon-remove"></span>@endif</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $concat_persons->appends(request()->input())->links() }}
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
