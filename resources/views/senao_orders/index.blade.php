@extends('layouts.master')

@section('title', '神腦訂單列表')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <a href="{{route('senao_orders.index')}}">神腦訂單列表</a>
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><i class="fa fa-shopping-bag"></i> 交易狀況</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            @if(session('msg'))
                @if(session('msg')=='')
                    <div class="alert alert-success text-center">{{'Success'}}</div>
                @else
                    <div class="alert alert-danger text-center">{{session('msg')}}</div>
                @endif
            @endif

            @if(session('msgs'))
                <div class="alert-danger alert text-center">
                    @foreach(session('msgs') as $msg)
                        {{$msg}} <br>

                    @endforeach
                </div>
        @endif
        <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="row">
                                <form name="filter_form" action="{{route('senao_orders.index')}}" method="get">

                                    <div class="col-md-2">
                                        <label>訂單狀態</label>
                                        <select name="status_filter" class="form-control form-control-sm">
                                            <option value="All" @if('All'==$status_filter) selected
                                                @endif>All
                                            </option>
                                            @foreach($senao_order_status_names as $status_name)
                                                <option @if($status_name==$status_filter) selected
                                                        @endif value="{{$status_name}}">{{$status_name}}</option>
                                            @endforeach
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
                                            @foreach(['create_date'] as $col)
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
                                    <form action="{{route('senao_orders.index')}}" method="get">
                                        <div class="form-inline">
                                            <select name="search_type" class="form-group form-control"
                                                    style="width: 100%;">
                                                <option value="1" @if(request()->get('search_type')==1) selected @endif>
                                                    業務系統編號
                                                </option>
                                                <option value="2" @if(request()->get('search_type')==2) selected @endif>
                                                    神腦編號
                                                </option>

                                                <option value="3" @if(request()->get('search_type')==3) selected @endif>
                                                    收貨人
                                                </option>
                                                <option value="4" @if(request()->get('search_type')==4) selected @endif>
                                                    料號
                                                </option>
                                                <option value="5" @if(request()->get('search_type')==5) selected @endif>
                                                    出貨單號
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
                                        {{--                                        <input hidden name="code_filter" value="{{$code_filter}}">--}}
                                        {{--                                        <input hidden name="user_filter" value="{{$user_filter}}">--}}
                                        <input hidden name="status_filter" value="{{$status_filter}}">
                                        <input hidden name="date_from" value="{{$date_from}}">
                                        <input hidden name="date_to" value="{{$date_to}}">
                                        <input hidden name="sortBy" value="{{$sortBy}}">
                                    </form>
                                    <!-- /.search form -->


                                </div>

                                <div class="col-md-1">
                                    <label>特殊功能</label><br>
                                    <div class="inline">
                                        <form action="{{ route('senao_orders.import') }}" method="POST"
                                                     enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="file" class="form-control-file">
                                            <button class="btn btn-success btn-sm">匯入神腦訂單</button>
                                        </form>


                                    </div>
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



                                function set_status_to_return() {
                                    var inputs = document.getElementsByName("get_code");
                                    var ids = [];
                                    for (var i = 0; i < inputs.length; i++) {
                                        if (inputs[i].checked ? 1 : 0) {
                                            ids.push(inputs[i].id);
                                        }
                                    }
                                    $.ajax({
                                        type: "POST",
                                        url: '{{route('senao_orders.set_status_to_return')}}',
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

                                                msg += '神腦編號:' + key + '\t' + value;
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

                                function exportSenaoOrders() {
                                    var inputs = document.getElementsByName("get_code");
                                    var ids = [];
                                    for (var i = 0; i < inputs.length; i++) {
                                        if (inputs[i].checked ? 1 : 0) {
                                            ids.push(inputs[i].id);
                                        }
                                    }
                                    $.ajax({
                                        type: "POST",
                                        url: '{{route('senao_orders.export')}}',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                        },
                                        data: {
                                            ids: ids,
                                        },
                                        success: function (data) {
                                            console.log(data);
                                            return;

                                        },
                                        error: function (data) {
                                            console.log(data);
                                            alert('伺服器出了點問題，稍後再重試');
                                        }
                                    });
                                    return;

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
                                    <th style="width:2%"></th>
                                    <th class="text-center" style="width:10%">業務系統編號</th>
                                    <th class="text-center" style="width:10%">神腦編號</th>
                                    <th class="text-center" style="width:8%">收貨人</th>
                                    <th class="text-center" style="width:10%">料號<br>商品名稱</th>
                                    <th class="text-center" style="width:5%">Status</th>
                                    <th class="text-center" style="width:5%">Amount</th>
                                    <th class="text-center" style="width:15%">出貨單號 <br>物流公司</th>
                                    <th class="text-center" style="width:13%">建單日期</th>
                                    <th class="text-center" style="width:13%">付款日期</th>
                                    <th class="text-center" style="width:20%">Other</th>
                                </tr>
                                </thead>
                                <button onclick="select_all()" class="btn-sm btn-dark">全選</button>
                                <button onclick="unselect_all()" class="btn-sm btn-dark">取消全選</button>

                                <script>
                                    function exportFormSubmit(){
                                        var inputs = document.getElementsByName("get_code");
                                        var ids = [];
                                        for (var i = 0; i < inputs.length; i++) {
                                            if (inputs[i].checked ? 1 : 0) {
                                                ids.push(inputs[i].id);
                                            }
                                        }

                                        var exportForm = document.getElementById('exportForm');
                                        var input = document.createElement('input');//prepare a new input DOM element
                                        input.setAttribute('name', 'ids');//set the param name
                                        input.setAttribute('value', ids);//set the value
                                        input.setAttribute('type', 'hidden');//set the type, like "hidden" or other

                                        exportForm.appendChild(input);//append the input to the form
                                        // var formData = $("#exportForm").serializeArray();
                                        // console.log(formData);
                                        return true;

                                    }

                                </script>


                                <form action="{{route('senao_orders.export')}}" method="post" name="exportForm" id="exportForm" onsubmit="return exportFormSubmit()">
                                    @csrf

                                    <div style="float: right;display: inline-block" class="inline">
{{--                                        <select id="function_select" name="function_select"--}}
{{--                                                class="select2-container form-control">--}}
{{--                                            <option value="匯出">匯出</option>--}}
{{--                                            <option value="退貨">設為退貨</option>--}}
{{--                                        </select>--}}

                                        <button style="display: inline;width: 100%" class="btn-sm btn-dark"
                                                type="submit"> 匯出已選中
                                        </button>
                                        <button style="display: inline;width: 100%" class="btn-sm btn-dark" onclick="set_status_to_return()">
                                            設定退貨
                                        </button>
                                        {{--                                        <button class="btn-sm btn-dark" onclick="exportOrders()">匯出已選中</button>--}}

                                    </div>


                                    @foreach ($senao_orders as $senao_order)
                                        {{--                                    @if($order->is_deleted)--}}
                                        {{--                                        @continue--}}
                                        {{--                                    @endif--}}

                                        <tr ondblclick="" class="text-center">
                                            <td class="align-middle " style="vertical-align: middle">
                                                <input type="checkbox" id="{{$senao_order->id}}"
                                                       name="get_code">
                                            </td>


                                            <td class="align-middle " style="vertical-align: middle">
                                                #{{ $senao_order->order->no}}
                                            </td>
                                            <td class="align-middle "
                                                style="vertical-align: middle">{{ $senao_order->seq_id}}
                                            </td>
                                            <td class="align-middle " style="vertical-align: middle">
                                                @if($senao_order->receiver)
                                                    {{$senao_order->receiver}}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="align-middle " style="vertical-align: middle">
                                                {{$senao_order->senao_isbn}}
                                                <br>
                                                {{$senao_order->product_name}}
                                            </td>
                                            @switch($senao_order->status)
                                                @case('完成出貨')
                                                @php($css='label label-success')
                                                @break
                                                @case('延遲出貨')
                                                @php($css='label label-warning')
                                                @break
                                                @case('無法出貨')
                                                @php($css='label label-danger')
                                                @break
                                                @case('退貨')
                                                @php($css='label label-danger')
                                                @break
                                                @default
                                                @php($css='label label-primary')
                                                @break
                                            @endswitch
                                            <td class="align-middle " style="vertical-align: middle"><label
                                                    class="label{{$css}}"
                                                    style="min-width:60px;display: inline-block">@if($senao_order->status){{ $senao_order->status }}@else
                                                        無@endif</label>

                                            <td class="align-middle "
                                                style="vertical-align: middle">{{round($senao_order->price * $senao_order->quantity)}}</td>
                                            <td class="align-middle " style="vertical-align: middle">
                                                {{$senao_order->shipment_code}}
                                                <br>
                                                {{$senao_order->shipment_company}}
                                            </td>
                                            <td class="align-middle "
                                                style="vertical-align: middle">{{date("Y-m-d", strtotime($senao_order->create_date))}}</td>
                                            <td class="align-middle "
                                                style="vertical-align: middle">@if($senao_order->pay_date){{date("Y-m-d", strtotime($senao_order->pay_date))}}@endif</td>


                                            <td class="align-middle" style="vertical-align: middle;">
                                                <script>
                                                    function order_edit(order_id) {
                                                        // console.log(encodeURIComponent(window.location.href));
                                                        window.location.href = '/orders/' + order_id + '/edit' + '?source_html=' + encodeURIComponent(window.location.href);
                                                    }
                                                </script>

                                                <a href="{{route('orders.detail',$senao_order->order->id)}}"
                                                   class="btn btn-xs btn-primary">詳細</a>
                                                {{--                                            <br>--}}
                                                <a onclick="order_edit({{$senao_order->order->id}})"
                                                   class="btn btn-xs btn-primary">編輯</a>
                                                <br>
                                                {{--                                            <a href="{{route('orders.export',$order->id)}}"--}}
                                                {{--                                               class="btn-xs btn btn-primary">匯出</a>--}}
                                                {{--                                            <br>--}}
                                                {{--                                            <a onclick="copyOnclick('{{$order->no}}','{{$order->id}}')"--}}
                                                {{--                                               class="btn-xs btn btn-primary">複製</a>--}}
                                                {{--                                            <br>--}}

                                                {{--                                            @if( (Auth::user()->level==2 || Auth::user()->level==0) && $order->status==0 )--}}
                                                {{--                                                <form action="{{route('orders.delete',$order->id)}}"--}}
                                                {{--                                                      method="post"--}}
                                                {{--                                                      style="display: inline-block">--}}
                                                {{--                                                    @csrf--}}
                                                {{--                                                    <button type="submit" class="btn btn-xs btn-danger"--}}
                                                {{--                                                            onclick="return confirm('確定是否刪除')">刪除--}}
                                                {{--                                                    </button>--}}
                                                {{--                                                </form>--}}
                                                {{--                                            @endif--}}

                                            </td>
                                        </tr>
                                    @endforeach
                                </form>
                            </table>


                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{--                            {{$orders->links()}}--}}
                            {{ $senao_orders->appends(request()->input())->links() }}

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
