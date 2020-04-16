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
{{--                            <div class="row">--}}
{{--                                <form name="filter_form" action="{{route('customers.index')}}" method="get">--}}
{{--                                    <div class="col-md-2 col-3">--}}
{{--                                        <label>客戶篩選</label>--}}
{{--                                        <select name="user_filter" class="form-control form-control-sm"--}}
{{--                                                value="{{$user_filter}}">--}}
{{--                                            <option value="0" @if($user_filter==0) selected @endif>全部客戶</option>--}}
{{--                                            <option value="1" @if($user_filter==1) selected @endif>我的客戶</option>--}}
{{--                                            <option value="2" @if($user_filter==2) selected @endif>未指定業務</option>--}}

{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-2 col-3">--}}
{{--                                        <label>狀態篩選</label>--}}
{{--                                        <select multiple name="status_filter[]"--}}
{{--                                                class="form-control form-control-sm">--}}
{{--                                            @foreach(['0','1','2','3','4','5'] as $col)--}}
{{--                                                <option @if($col==$status_filter) selected--}}
{{--                                                        @endif value="{{$col}}">{{$status_text[$loop->index]}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-2 col-3">--}}
{{--                                        <label>排序方式</label>--}}
{{--                                        <select multiple name="sortBy[]" class="form-control form-control-sm">--}}
{{--                                            @foreach(['create_date','city','area','user_id','status'] as $col)--}}
{{--                                                <option @if($col==$sortBy) selected--}}
{{--                                                        @endif value="{{$col}}">{{$sortBy_text[$loop->index]}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-1 col-3">--}}
{{--                                        <label>篩選按鈕</label><br>--}}
{{--                                        <button type="submit" class="w-100 btn btn-sm bg-blue">Filter</button>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                                <div class="col-md-3 col-3">--}}
{{--                                    <label>搜尋</label><br>--}}
{{--                                    <!-- search form (Optional) -->--}}
{{--                                    <form roe="form" action="{{route('customers.index')}}" method="get">--}}
{{--                                        <div class="form-inline">--}}
{{--                                            <select name="search_type" class="form-group">--}}
{{--                                                <option value="1">客戶名稱</option>--}}
{{--                                                <option value="2">地區</option>--}}

{{--                                            </select>--}}
{{--                                            <br>--}}
{{--                                            <div class="inline">--}}
{{--                                                <input type="text" name="search_info" class="form-control"--}}
{{--                                                       placeholder="Search...">--}}
{{--                                                <button type="submit" id="search-btn" style="cursor: pointer"--}}
{{--                                                        class="btn btn-flat"><i class="fa fa-search"></i>--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                    <!-- /.search form -->--}}

{{--                                </div>--}}
{{--                                <div class="col-md-1 col-3">--}}
{{--                                    <label>特殊功能</label><br>--}}
{{--                                    <a class="btn btn-success btn-sm" href="{{route('customers.create')}}">新增客戶</a>--}}
{{--                                </div>--}}


{{--                            </div>--}}


                        </div>

                        <!-- /.box-header -->
                        <div class="box-body ">

                            <table class="table table-bordered table-hover" width="100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th class="text-center" style="width:15%">姓名</th>
                                    <th class="text-center" style="width:30%">公司</th>
                                    <th class="text-center" style="width:20%">Email</th>
                                    <th class="text-center" style="width:10%">在職狀態</th>
                                </tr>
                                </thead>

                                @foreach ($concat_persons as $concat_person)

                                    <tr ondblclick="" class="text-center">
                                        <td class="text-left">{{ $concat_person->name }}</td>
                                        <td>{{ $concat_person->customer->name  }}</td>
                                        <td>{{ $concat_person->email  }}</td>
                                        @if ($concat_person->is_left==0)
                                            @php($css='label label-primary')
                                        @elseif($concat_person->is_left==1)
                                            @php($css='label label-warning')
                                        @endif
                                        <td class="align-middle " style="vertical-align: middle"><label class="label{{$css}}"
                                                                                                        style="min-width:60px;display: inline-block">
                                                @if($concat_person->is_left==0) 在職 @else 離職 @endif
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $concat_persons->links() }}
                        </div>
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
