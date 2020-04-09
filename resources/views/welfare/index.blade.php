@extends('layouts.master')

@section('title', '福利狀況')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                福利狀況
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 福利狀況</a></li>
                <li class="active">福利狀況列表</li>
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
                        <div class="box-header">
                            <div class="row">
                                <form name="filter_form" action="{{route('welfare_status.index')}}" method="get">
                                    <div class="col-md-2 col-3">
                                        <label>客戶篩選</label>
                                        <select name="user_filter" class="form-control form-control-sm"
                                                value="{{$user_filter}}">
                                            <option value="0" @if($user_filter==0) selected @endif>全部客戶</option>
                                            <option value="1" @if($user_filter==1) selected @endif>我的客戶</option>
                                            <option value="2" @if($user_filter==2) selected @endif>未指定業務</option>

                                        </select>
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <label>狀態篩選</label>
                                        <select multiple name="status_filter[]"
                                                class="form-control form-control-sm">
                                            <option value="-1">All</option>
                                            @foreach(['0','1','2'] as $col)
                                                <option @if($col==$status_filter) selected
                                                        @endif value="{{$col}}">{{$status_names[$loop->index]}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <label>排序方式</label>
                                        <select multiple name="sortBy[]" class="form-control form-control-sm">
                                            @foreach(['customer_id','welfare_id','track_status','budget','update_date'] as $col)
                                                <option @if($col==$sortBy) selected
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
                                    <form action="{{route('welfare_status.index')}}" method="get">
                                        <div class="form-inline">
                                            <select name="search_type" class="form-group">
                                                <option value="1">公司名稱</option>
                                                <option value="2">目的</option>
                                                {{--                                                <option value="3">福利類別</option>--}}
                                            </select>
                                            <br>
                                            <div class="inline">
                                                <input type="text" name="search_info" class="form-control"
                                                       placeholder="Search...">
                                                <button type="submit" id="search-btn" style="cursor: pointer"
                                                        class="btn btn-flat"><i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.search form -->

                                </div>

                                <div class="box-tools col-md-1 col-3 right ">
                                    <label>特殊功能</label><br>
                                    <a class="btn btn-success btn-sm"
                                       href="{{route('welfare_status.add_welfare_type')}}">新增福利類別</a>
                                    <br><br>

                                    <a class="btn btn-success btn-sm"
                                       href="">新增客戶福利</a>
                                </div>
                            </div>
                            {{--<div class="box-tools">--}}
                            {{--<a class="btn btn-success btn-sm" href="{{ route('products.create') }}">新增福利者</a>--}}
                            {{--</div>--}}

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover" style="width: 100%">
                                <thead style="background-color: lightgray">

                                <tr class="text-center">
                                    <th class="text-center" style="width: 20%">客戶名稱</th>
                                    <th class="text-center" style="width: 10%">目的</th>
                                    <th class="text-center" style="width: 10%">福利類別</th>
                                    <th class="text-center" style="width: 10%">交易狀況</th>
                                    <th class="text-center" style="width: 10%">預算</th>
                                    <th class="text-center" style="width: 10%">更新日期</th>
                                    <th class="text-center" style="width: 10%">負責業務</th>

                                    <th class="text-center" style="width: 10%">其他功能</th>


                                </tr>
                                </thead>
                                @foreach ($welfare_statuses as $welfare_status)
                                    @if(count($welfare_status->welfare_types)>0 || ($welfare_status->budget !='0' && $welfare_status->budget !=''))
                                        <tr class="text-center">
                                            <td class="text-left">
                                                <svg class="bi bi-briefcase-fill" width="1em" height="1em"
                                                     viewBox="0 0 16 16" fill="currentColor"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                          d="M0 12.5A1.5 1.5 0 001.5 14h13a1.5 1.5 0 001.5-1.5V6.85L8.129 8.947a.5.5 0 01-.258 0L0 6.85v5.65z"
                                                          clip-rule="evenodd"/>
                                                    <path fill-rule="evenodd"
                                                          d="M0 4.5A1.5 1.5 0 011.5 3h13A1.5 1.5 0 0116 4.5v1.384l-7.614 2.03a1.5 1.5 0 01-.772 0L0 5.884V4.5zm5-2A1.5 1.5 0 016.5 1h3A1.5 1.5 0 0111 2.5V3h-1v-.5a.5.5 0 00-.5-.5h-3a.5.5 0 00-.5.5V3H5v-.5z"
                                                          clip-rule="evenodd"/>
                                                </svg>&nbsp; {{$welfare_status->customer->name }}</td>
                                            <td>{{ $welfare_status->welfare_name }}</td>
                                            <td class="text-left">
                                                @foreach($welfare_status->welfare_types as $welfare_type)
                                                    <li>{{$welfare_type->welfare_type_name->name}}</li>
                                                @endforeach
                                            </td>
                                            @if ($welfare_status->track_status==0)
                                                @php($css='label label-danger')
                                            @elseif($welfare_status->track_status==1)
                                                @php($css='label label-info')
                                            @elseif($welfare_status->track_status==2)
                                                @php($css='label label-success')
                                            @endif
                                            <td><label class="label{{$css}}"
                                                       style="min-width:60px;display: inline-block">{{ $status_names[$welfare_status->track_status] }}</label>
                                            </td>
                                            <td>{{ $welfare_status->budget}}</td>
                                            <td>{{date("Y-m-d", strtotime($welfare_status->update_date))}}</td>
                                            <td class="text-left">{{\App\Customer::find($welfare_status->customer_id)->user->name}}</td>
                                            <td class="text-center">
                                                {{--                                            <a href=""--}}
                                                {{--                                               class="btn btn-xs btn-success">新增類別</a>--}}
                                                <a href="{{route('welfare_status.edit',$welfare_status->id)}}"
                                                   class="btn btn-xs btn-primary">編輯</a>

                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $welfare_statuses->appends(request()->input())->links()}}
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
