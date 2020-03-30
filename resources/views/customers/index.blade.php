@extends('layouts.master')

@section('title', '客戶列表')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <a href="{{route('customers.index')}}">客戶管理</a>
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 客戶管理</a></li>
                <li class="active">客戶列表</li>
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
{{--                            <form action="{{route('customers.index')}}">--}}
                                <div class="row">
                                    <form name="filter_form" action="{{route('customers.index')}}" method="get">
                                        <div class="col-md-2 col-3">
                                            <label>客戶篩選</label>
                                            <select name="user_filter" class="form-control form-control-sm"
                                                    value="{{$user_filter}}">
                                                <option value="0" @if($user_filter==0) selected @endif>全部客戶</option>
                                                <option value="1" @if($user_filter==1) selected @endif>我的客戶</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-3">
                                            <label>狀態篩選</label>
                                            <select multiple name="status_filter[]"
                                                    class="form-control form-control-sm">
                                                @foreach(['0','1','2','3','4'] as $col)
                                                    <option @if($col==$status_filter) selected
                                                            @endif value="{{$col}}">{{$status_text[$loop->index]}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-3">
                                            <label>排序方式</label>
                                            <select multiple name="sortBy[]" class="form-control form-control-sm">
                                                @foreach(['create_date','city','area','user_id','status'] as $col)
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
                                        <form roe="form" action="{{route('customers.index')}}" method="get">
                                            <div class="form-inline">
                                                <select name="search_type" class="form-group">
                                                    <option value="1">公司名稱</option>
                                                    <option value="2">地區</option>

                                                </select>
                                                <br>
                                                <div class="inline">
                                                    <input type="text" name="search_info" class="form-control"
                                                           placeholder="Search...">
                                                    <button type="submit" id="search-btn"
                                                            class="btn btn-flat"><i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- /.search form -->

                                    </div>
                                    <div class="col-md-1 col-3">
                                        <label>特殊功能</label><br>
                                        <a class="btn btn-success btn-sm" href="{{route('customers.create')}}">新增客戶</a>
                                    </div>


                                </div>

{{--                            </form>--}}

                        </div>

                        <!-- /.box-header -->
                        <div class="box-body ">

                            <table class="table table-bordered table-hover" width="100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th class="text-center" style="width:15%">客戶名稱</th>
                                    <th class="text-center" style="width:15%">電話</th>
                                    <th class="text-center" style="width:15%">規模</th>
                                    <th class="text-center" style="width:15%">縣市地區</th>
                                    <th class="text-center" style="width:15%">狀態</th>
                                    <th class="text-center" style="width: 10%">Sales</th>
                                    <th class="text-center" style="width: 20%">其他功能</th>

                                </tr>
                                </thead>

                                <script>
                                    function dbclick_on_customer(customer_id) {
                                        window.location.href = '/customers/' + customer_id + '/record';
                                    }
                                </script>
                                @foreach ($customers as $customer)
                                    @if($customer->is_deleted==1)
                                        @continue
                                    @endif

                                    <tr ondblclick="dbclick_on_customer({{$customer->id}})" class="text-center">
                                        <td class="text-left">{{ $customer->name }}</td>
                                        <td>{{ $customer->phone_number  }}</td>
                                        <td>{{ $customer->scales  }}人</td>
                                        <td class="text-left">{{ $customer->city }}{{$customer->area}} </td>
                                        @if ($customer->status==1)
                                            @php($css='label label-warning')
                                        @elseif($customer->status==2)
                                            @php($css='label label-success')
                                        @elseif($customer->status==3)
                                            @php($css='label label-info')
                                        @elseif($customer->status==4)
                                            @php($css='label label-primary')
                                        @endif
                                        <td><label class="label{{$css}}"
                                                   style="min-width:60px;display: inline-block">{{ $status_text[$customer->status] }}</label>
                                        </td>
                                        <td class="text-left">{{ $customer->user->name }} </td>
                                        <td class="text-left">
                                            <a href="{{ route('customers.show', $customer->id) }}"
                                               class="btn btn-xs btn-primary">詳細</a>
                                            <a href="{{ route('customers.edit', $customer->id) }}"
                                               class="btn btn-xs btn-primary">編輯</a>

                                            <a href="{{ route('customers.record', $customer->id) }}"
                                               class="btn btn-xs btn-primary">紀錄</a>
                                            @if($customer->user_id==Auth::user()->id or Auth::user()->level==2)
                                                <form action="{{ route('customers.delete', $customer->id) }}"
                                                      method="post"
                                                      style="display: inline-block">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-danger"
                                                            onclick="return confirm('確定是否刪除')">刪除
                                                    </button>
                                                </form>
                                            @endif
                                        </td>


                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $customers->appends(request()->input())->links() }}
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
