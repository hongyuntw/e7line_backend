@extends('layouts.master')

@section('title', '訂單列表')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <a href="{{route('orders.index')}}">訂單列表</a>
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><i class="fa fa-shopping-bag"></i> 交易狀況</li>
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
                                <form name="filter_form" action="{{route('orders.index')}}" method="get">
                                    <div class="col-md-2">
                                        <label>業務</label>
                                        <select name="user_filter" class="form-control form-control-sm">
                                            <option value="-1" @if($user_filter==-1) selected @endif>All</option>
                                            @foreach($users as $user)
                                                <option @if($user->id==$user_filter) selected
                                                        @endif value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>訂單狀態</label>
                                        <select name="status_filter" class="form-control form-control-sm">
                                            <option value="-1" @if(-1==$status_filter) selected
                                                @endif>All
                                            </option>
                                            @foreach($order_status_names as $status_name)
                                                <option @if($loop->index==$status_filter) selected
                                                        @endif value="{{$loop->index}}">{{$status_name}}</option>
                                            @endforeach
                                        </select>
                                        <label>拋單狀態</label>
                                        <select name="code_filter" class="form-control form-control-sm">
                                            <option value="-1" @if(-1==$code_filter) selected @endif>All</option>
                                            <option value="0" @if(0==$code_filter) selected @endif>未拋單</option>
                                            <option value="1" @if(1==$code_filter) selected @endif>已拋單</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>日期篩選</label>
                                        <input type="date" class="form-control" name="date_from"
                                               value="@if($date_from != null){{($date_from)}}@endif">
                                        至
                                        <input type="date" class="form-control" name="date_to"
                                               value="@if($date_to != null){{$date_to}}@endif">

                                    </div>

                                    <div class="col-md-2">
                                        <label>排序方式</label>
                                        <select name="sortBy" class="form-control form-control-sm">
                                            @foreach(['create_date','receive_date'] as $col)
                                                <option @if($sortBy == $col) selected
                                                        @endif value="{{$col}}">{{$sortBy_text[$loop->index]}}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        <button type="submit" class=" btn btn-sm bg-blue" style="width: 100%">篩選
                                        </button>
                                    </div>

                                </form>
                                <div class="col-md-3">
                                    <label>搜尋</label><br>
                                    <!-- search form (Optional) -->
                                    <form roe="form" action="{{route('orders.index')}}" method="get">
                                        <div class="form-inline">
                                            <select name="search_type" class="form-group form-control"
                                                    style="width: 100%;">
                                                <option value="1" @if(request()->get('search_type')==1) selected @endif>
                                                    訂單編號
                                                </option>
                                                <option value="2" @if(request()->get('search_type')==2) selected @endif>
                                                    客戶名稱
                                                </option>

                                                <option value="3" @if(request()->get('search_type')==3) selected @endif>
                                                    統編
                                                </option>
                                                <option value="4" @if(request()->get('search_type')==4) selected @endif>
                                                    訂購人名稱
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
                                        <input hidden name="code_filter" value="{{$code_filter}}">
                                        <input hidden name="user_filter" value="{{$user_filter}}">
                                        <input hidden name="status_filter" value="{{$status_filter}}">
                                        <input hidden name="date_from" value="{{$date_from}}">
                                        <input hidden name="date_to" value="{{$date_to}}">
                                        <input hidden name="sortBy" value="{{$sortBy}}">


                                    </form>
                                    <!-- /.search form -->


                                </div>

                                <div class="col-md-1">
                                    <label>特殊功能</label><br>
                                    <a class="btn btn-success btn-sm" href="{{route('orders.create')}}">新增訂單</a>
                                </div>

                            </div>

                            {{--                            </div>--}}


                        </div>


                        <!-- /.box-header -->
                        <div class="box-body ">
                            <script>
                                function select_all() {
                                    var select_boxes = document.getElementsByName("get_code");
                                    for (var i = 0; i < select_boxes.length; i++) {
                                        select_boxes[i].checked = 1;
                                    }
                                }

                                function unselect_all() {
                                    var select_boxes = document.getElementsByName("get_code");
                                    for (var i = 0; i < select_boxes.length; i++) {
                                        select_boxes[i].checked = 0;
                                    }
                                }

                                function get_code() {
                                    var inputs = document.getElementsByName("get_code");
                                    var ids = [];
                                    for (var i = 0; i < inputs.length; i++) {
                                        if (inputs[i].checked ? 1 : 0) {
                                            ids.push(inputs[i].id);
                                        }
                                    }
                                    $.ajax({
                                        type: "POST",
                                        url: '{{route('orders.index_gex_code')}}',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                        },
                                        data: {
                                            ids: ids,
                                        },
                                        success: function (data) {
                                            console.log(data);
                                            // var msg = data.responseJSON;
                                            // console.log(msg);
                                            var msg = '';
                                            for (let [key, value] of Object.entries(data)) {
                                                // msg_str += value;
                                                // console.log(key);
                                                msg += '訂單編號:' + key + '\t' + value;
                                                msg += '\n';
                                            }
                                            alert(msg);
                                            // tmp = false;
                                            window.location.reload();
                                        },
                                        error: function () {
                                            alert('伺服器出了點問題，稍後再重試');
                                        }
                                    });
                                }
                                function exportOrders(){
                                    var inputs = document.getElementsByName("get_code");
                                    var ids = [];
                                    for (var i = 0; i < inputs.length; i++) {
                                        if (inputs[i].checked ? 1 : 0) {
                                            ids.push(inputs[i].id);
                                        }
                                    }
                                    $.ajax({
                                        type: "POST",
                                        url: '{{route('orders.exportFromIndex')}}',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                        },
                                        data: {
                                            ids: ids,
                                        },
                                        success: function (data) {},
                                        error: function () {
                                            alert('伺服器出了點問題，稍後再重試');
                                        }
                                    });

                                }
                                function changeStatus2Success() {
                                    var inputs = document.getElementsByName("get_code");
                                    var ids = [];
                                    for (var i = 0; i < inputs.length; i++) {
                                        if (inputs[i].checked ? 1 : 0) {
                                            ids.push(inputs[i].id);
                                        }
                                    }
                                    $.ajax({
                                        type: "POST",
                                        url: '{{route('orders.changeStatus2Success')}}',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                        },
                                        data: {
                                            ids: ids,
                                        },
                                        success: function (data) {
                                            console.log(data);
                                            var msg = '';
                                            for (let [key, value] of Object.entries(data)) {
                                                msg += '訂單編號:' + key + '\t' + value;
                                                msg += '\n';
                                            }
                                            alert(msg);
                                            window.location.reload();
                                        },
                                        error: function () {
                                            alert('伺服器出了點問題，稍後再重試');
                                        }
                                    });
                                }
                            </script>

                            <table class="table table-bordered table-hover" width="100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th style="width:3%"></th>
                                    <th class="text-center" style="width:12%">Order</th>
                                    <th class="text-center" style="width:18%">Customer</th>
                                    <th class="text-center" style="width:8%">統編</th>
                                    <th class="text-center" style="width:5%">Sales</th>
                                    <th class="text-center" style="width:5%">Status</th>
                                    <th class="text-center" style="width:5%">Amount</th>
                                    <th class="text-center" style="width:10%">建單日期</th>
                                    <th class="text-center" style="width:10%">收貨日期</th>
                                    <th class="text-center" style="width:10%">Note</th>

                                    <th class="text-center" style="width:20%">Other</th>
                                </tr>
                                </thead>
                                <button onclick="select_all()" class="btn-sm btn-dark">全選</button>
                                <button onclick="unselect_all()" class="btn-sm btn-dark">取消全選</button>

                                <div style="float: right">
                                    @if(Auth::user()->level==2)
                                        <button class="btn-sm btn-dark" onclick="get_code()">拋單已選中</button>

                                    @endif
                                        <button class="btn-sm btn-dark" onclick="changeStatus2Success()">設為已完成</button>
{{--                                        <button class="btn-sm btn-dark" onclick="exportOrders()">匯出已選中</button>--}}

                                </div>

                                @foreach ($orders as $order)
                                    @if($order->is_deleted)
                                        @continue
                                    @endif

                                    <tr ondblclick="" class="text-center">
                                        <td>
                                            <input type="checkbox" id="{{$order->id}}"
                                                   name="get_code">
                                        </td>

                                        <td class="text-left">#{{ $order->no}} &nbsp  &nbsp

                                            @if($order->code)
                                                <span style="color: red;font-weight: bold">
                                                {{$order->code}}
                                                </span>
                                            @endif

                                            <br>
                                            @if($order->email)
                                                {{$order->email}}
                                            @else
                                                no email
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->customer)
                                                {{$order->customer->name}}
                                            @else
                                                {{$order->other_customer_name}}
                                            @endif
                                            <br>
                                                by &nbsp
                                                @if($order->business_concat_person)
                                                    {{$order->business_concat_person->name}}
                                                @else
                                                    {{$order->other_concat_person_name}}
                                                @endif
                                        </td>
                                        <td class="text-left">{{ ($order->tax_id)}}</td>
                                        <td>
                                            {{$order->user->name}}
                                        </td>

                                        @switch($order->status)
                                            @case(0)
                                            @php($css='label label-danger')
                                            @break
                                            @case(1)
                                            @php($css='label label-warning')
                                            @break
                                            @case(2)
                                            @php($css='label label-primary')
                                            @break
                                            @case(3)
                                            @php($css='label label-success')
                                            @break
                                            @case(4)
                                            @php($css='label label-primary')
                                            @break
                                            @default
                                            @break
                                        @endswitch
                                        <td class="align-middle " style="vertical-align: middle"><label
                                                class="label{{$css}}"
                                                style="min-width:60px;display: inline-block">{{ $order_status_names[$order->status] }}</label>

                                        <td>{{round($order->amount)+round($order->shipping_fee)}}</td>
                                        <td class="text-center">{{date("Y-m-d", strtotime($order->create_date))}}</td>
                                        <td class="text-center">
                                            @if($order->receive_date){{date("Y-m-d", strtotime($order->receive_date))}}@else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{$order->note}}
                                        </td>


                                        <td>
                                            <script>
                                                function order_edit(order_id) {
                                                    // console.log(encodeURIComponent(window.location.href));
                                                    window.location.href = '/orders/' + order_id + '/edit' + '?source_html=' + encodeURIComponent(window.location.href);
                                                }
                                            </script>

                                            <a href="{{route('orders.detail',$order->id)}}"
                                               class="btn btn-xs btn-primary">詳細</a>
                                            <a onclick="order_edit({{$order->id}})"
                                               class="btn btn-xs btn-primary">編輯</a>
                                            <a href="{{route('orders.export',$order->id)}}" class="btn-xs btn btn-primary">匯出</a>

                                            @if( Auth::user()->level==2)
                                                <form action="{{route('orders.delete',$order->id)}}"
                                                      method="post"
                                                      style="display: inline-block">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-danger"
                                                            onclick="return confirm('確定是否刪除')">刪除
                                                    </button>
                                                </form>
                                            @endif
                                            {{--                                            @if(Auth::user()->level!=0 && $order->status==1)--}}
                                            {{--                                                <button type="button" onclick="changeOrderStatusBack({{$order->id}})"--}}
                                            {{--                                                        class="btn btn-xs btn-primary">退回未處理--}}
                                            {{--                                                </button>--}}
                                            {{--                                            @endif--}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <script>
                                function changeOrderStatusBack(id) {
                                    console.log(id);
                                    $.ajax({
                                        type: "POST",
                                        url: '{{route('orders.changeStatusBack')}}',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                        },
                                        data: {
                                            id: id,
                                        },
                                        success: function (msg) {
                                            alert(msg);
                                            window.location.reload();
                                        },
                                        error: function () {
                                            alert('伺服器出了點問題，稍後再重試');
                                        }
                                    });

                                }
                            </script>
                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{--                            {{$orders->links()}}--}}
                            {{ $orders->appends(request()->input())->links() }}

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
