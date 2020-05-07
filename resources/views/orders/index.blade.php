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
                            {{--                            <div class="row">--}}
                            {{--                                <form name="filter_form" action="{{route('mails.index')}}" method="get">--}}
                            {{--                                  --}}
                            {{--                                    <div class="col-md-2 col-3">--}}
                            {{--                                        <label>在職狀態篩選</label>--}}
                            {{--                                        <select name="is_left"--}}
                            {{--                                                class="form-control form-control-sm">--}}
                            {{--                                            @foreach(['-1','0','1'] as $col)--}}
                            {{--                                                <option @if($col==$is_left) selected--}}
                            {{--                                                        @endif value="{{$col}}">{{$status_text[$loop->index]}}</option>--}}
                            {{--                                            @endforeach--}}
                            {{--                                        </select>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="col-md-2 col-3">--}}
                            {{--                                        <label>收信狀態篩選</label>--}}
                            {{--                                        <select  name="want_receive_mail"--}}
                            {{--                                                 class="form-control form-control-sm">--}}
                            {{--                                            @foreach(['-1','1','0'] as $col)--}}
                            {{--                                                <option @if($col==$want_receive_mail) selected--}}
                            {{--                                                        @endif value="{{$col}}">@if($col==-1)---@elseif($col==1)是@else否@endif</option>--}}
                            {{--                                            @endforeach--}}
                            {{--                                        </select>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="col-md-2 col-3">--}}
                            {{--                                        <label>排序方式</label>--}}
                            {{--                                        <select multiple name="sortBy[]" class="form-control form-control-sm">--}}
                            {{--                                            @foreach(['create_date','name','customer_name','is_left','area','want_receive_mail'] as $col)--}}
                            {{--                                                <option @if(is_array($sortBy))--}}
                            {{--                                                        @if(in_array($col, $sortBy))--}}
                            {{--                                                        selected--}}
                            {{--                                                        @endif--}}
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
                            {{--                                    <form roe="form" action="{{route('mails.index')}}" method="get">--}}
                            {{--                                        <div class="form-inline">--}}
                            {{--                                            <select name="search_type" class="form-group form-control">--}}
                            {{--                                                <option value="1" @if(request()->get('search_type')==1) selected @endif>--}}
                            {{--                                                    姓名--}}
                            {{--                                                </option>--}}
                            {{--                                                <option value="2" @if(request()->get('search_type')==2) selected @endif>--}}
                            {{--                                                    公司(客戶)--}}
                            {{--                                                </option>--}}
                            {{--                                                <option value="3" @if(request()->get('search_type')==3) selected @endif>--}}
                            {{--                                                    地區--}}
                            {{--                                                </option>--}}
                            {{--                                            </select>--}}
                            {{--                                            <br>--}}
                            {{--                                            <div class="inline">--}}
                            {{--                                                <input type="text" name="search_info" class="form-control"--}}
                            {{--                                                       placeholder="Search..." value="@if(request()->get('search_info')) {{request()->get('search_info')}} @endif">--}}
                            {{--                                                <button type="submit" id="search-btn" style="cursor: pointer"--}}
                            {{--                                                        class="btn btn-flat"><i class="fa fa-search"></i>--}}
                            {{--                                                </button>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                        <input hidden name="is_left" value="{{$is_left}}">--}}
                            {{--                                        <input hidden name="want_receive_mail" value="{{$want_receive_mail}}">--}}

                            {{--                                        <select multiple name="sortBy[]" hidden>--}}
                            {{--                                            @if(request()->get('sortBy'))--}}
                            {{--                                                @foreach(request()->get('sortBy') as $col)--}}
                            {{--                                                    <option selected value="{{$col}}"></option>--}}
                            {{--                                                @endforeach--}}
                            {{--                                            @endif--}}
                            {{--                                        </select>--}}
                            {{--                                    </form>--}}
                            {{--                                    <!-- /.search form -->--}}

                            {{--                                </div>--}}
                            <div class="col-md-1 col-3">
                                <label>特殊功能</label><br>
                                <a class="btn btn-success btn-sm" href="{{route('orders.create')}}">新增訂單</a>
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
                                        url: '',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                        },
                                        data: {
                                            ids: ids,
                                        },
                                        success: function (msg) {
                                            window.location.reload();
                                            console.log(msg)
                                        }
                                    });
                                }
                            </script>

                            <table class="table table-bordered table-hover" width="100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th style="width:3%"></th>
                                    <th class="text-center" style="width:15%">Order</th>
                                    <th class="text-center" style="width:20%">Customer</th>
                                    <th class="text-center" style="width:8%">統編</th>
                                    <th class="text-center" style="width:8%">Sales</th>
                                    <th class="text-center" style="width:5%">Status</th>
                                    <th class="text-center" style="width:5%">Amount</th>
                                    <th class="text-center" style="width:10%">建單日期</th>
                                    <th class="text-center" style="width:10%">收貨日期</th>
                                    <th class="text-center" style="width:20%">Other</th>
                                </tr>
                                </thead>
                                <button onclick="select_all()" class="btn-sm btn-dark">全選</button>
                                <button onclick="unselect_all()" class="btn-sm btn-dark">取消全選</button>

                                <div style="float: right">
                                    <button class="btn-sm btn-dark" onclick="get_code()">拋單已選中</button>
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
                                        <td class="text-left">#{{ $order->code}} &nbsp by &nbsp
                                            @if($order->business_concat_person)
                                                {{$order->business_concat_person->name}}
                                            @else
                                                {{$order->other_concat_person_name}}
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
                                            @php($css='label label-success')
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

                                        <td>{{round($order->amount)}}</td>
                                        <td class="text-center">{{date("Y-m-d", strtotime($order->create_date))}}</td>
                                        <td class="text-center">
                                            @if($order->receive_date){{date("Y-m-d", strtotime($order->receive_date))}}@else
                                                -
                                            @endif
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
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{--                            {{ $concat_persons->appends(request()->input())->links() }}--}}
                            {{$orders->links()}}
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
