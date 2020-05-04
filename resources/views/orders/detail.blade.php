@extends('layouts.master')

@section('title', '交易詳細')

@section('content')
    <meta id="csrf_token" name="csrf_token" content="{{ csrf_token() }}"/>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                交易詳細
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('orders.index')}}"><i class="fa fa-shopping-bag"></i> 交易狀況</a></li>
                <li class="active">交易詳細</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            <div class="tabs">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <!-- /.box-header -->
                            <div class="box-body">

                                @if(session('alert')=='success')
                                    <div class="alert alert-success text-center">{{session('msg')}}</div>
                                @elseif(session('alert')=='failed')
                                    <div class="alert alert-danger text-center">{{session('msg')}}</div>
                                @endif


                                <div id="Customer">
                                    <h4 class="text-center">
                                        <label style="font-size: medium">Order Detail</label>
                                    </h4>
                                </div>

                                <table class="table table-striped" style="width: 100%">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 10%;">訂單編號</th>

                                        <th class="text-center" style="width: 20%;">客戶名稱</th>
                                        <th class="text-center" style="width: 20%;">抬頭</th>

                                        <th class="text-center" style="width: 10%;">訂購窗口</th>
                                        <th class="text-center" style="width: 10%;">負責業務</th>
                                        <th class="text-center" style="width: 10%;">日期</th>
                                        <th class="text-center" style="width: 5%;">狀態</th>
                                        <th class="text-center" style="width: 5%;">目的</th>


                                    </tr>
                                    </thead>
                                    <tr>
                                        <td class="text-center">
                                            @if($order->code)
                                                {{$order->code}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($order->customer)
                                                {{$order->customer->name}}
                                            @else
                                                {{$order->other_customer_name}}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($order->title)
                                                {{$order->title}}
                                            @else
                                                -
                                            @endif


                                        </td>
                                        <td class="text-center">
                                            @if($order->business_concat_person){{$order->business_concat_person->name}}
                                            @else {{$order->other_concat_person_name}}
                                            @endif</td>
                                        <td class="text-center">{{$order->user->name}}</td>
                                        <td class="text-center">{{$order->create_date}}</td>


                                        @switch($order->status)
                                            @case(0)
                                            @php($css='label label-danger')
                                            @break
                                            @case(1)
                                            @php($css='label label-warning')
                                            @break
                                            @case(2)
                                            @php($css='label label-info')
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
                                        <td><label
                                                class="label{{$css}}"
                                                style="min-width:40px;display: inline-block">{{ $order_status_names[$order->status] }}</label>

                                        <td class="text-center">{{$order->welfare->welfare_name}}</td>


                                    </tr>
                                </table>

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
                                    <label style="font-size: medium">Order Note</label>
                                </h4>
                                <table class="table table-striped" style="width: 100%">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 10%;">Email</th>
                                        <th class="text-center" style="width: 10%;">Phone</th>
                                        <th class="text-center" style="width: 10%;">統編</th>
                                        <th class="text-center" style="width: 10%;">收件地址</th>
                                        <th class="text-center" style="width: 10%;">最遲到貨</th>
                                        <th class="text-center" style="width: 10%;">收件日期</th>

                                    </tr>
                                    </thead>
                                    <tr>
                                        <td class="text-center">{{$order->email}}</td>
                                        <td class="text-center">{{$order->phone_number}}</td>
                                        <td class="text-center">{{$order->tax_id}}</td>
                                        <td class="text-center">{{$order->ship_to}}</td>
                                        <td class="text-center">
                                            @if($order->latest_arrival_date){{$order->latest_arrival_date}}
                                            @else -
                                            @endif </td>
                                        <td class="text-center">
                                            @if($order->receive_date){{$order->receive_date}}
                                            @else -
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <h4 class="text-center">
                                    <label style="font-size: medium">Payment</label>
                                </h4>

                                <table class="table table-striped" style="width: 100%">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 10%;">付款方式</th>
                                        <th class="text-center" style="width: 10%;">付款時間</th>
                                        <th class="text-center" style="width: 10%;">付款帳戶資訊</th>
                                        <th class="text-center" style="width: 10%;">後五碼</th>
                                        <th class="text-center" style="width: 10%;">備註</th>

                                    </tr>
                                    </thead>
                                    <tr>
                                        <td class="text-center">{{$payment_method_names[$order->payment_method]}}</td>
                                        <td class="text-center">{{$order->payment_date}}</td>
                                        <td class="text-center">{{$order->payment_account}}</td>
                                        <td class="text-center">{{$order->last_five_nums}}</td>
                                        <td class="text-center">{{$order->note}}</td>


                                    </tr>
                                </table>


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
                                            <label style="font-size: medium">Order items</label>
                                        </h4>
                                    </div>


                                    <table class="table table-striped" width="100%">
                                        <thead style="background-color: lightgray">
                                        <tr class="text-center">
                                            <th class="text-center" style="width: 25%;">Products</th>
                                            <th class="text-center" style="width: 15%;">規格</th>
                                            <th class="text-center" style="width: 15%;">ISBN</th>
                                            <th class="text-center" style="width: 10%;">Quantity</th>
                                            <th class="text-center" style="width: 10%;">Price</th>
                                            <th class="text-center" style="width: 10%;">Amount</th>

                                        </tr>
                                        </thead>
                                        @php($total_price=0)
                                        @foreach ($order_items  as $order_item)
                                            <tr class="text-center">
                                                <td>{{$order_item->product_relation->product->name}} {{$order_item->product_relation->product_detail->name}}</td>
                                                <td>
                                                    @if($order_item->spec_name)
                                                        {{$order_item->spec_name}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$order_item->product_relation->ISBN}}
                                                </td>
                                                <td>${{round($order_item->quantity)}}</td>
                                                <td>${{round($order_item->price)}}</td>
                                                <td>${{round($order_item->quantity*$order_item->price)}}</td>
                                                @php($total_price += round($order_item->quantity*$order_item->price))
                                            </tr>
                                        @endforeach
                                    </table>
                                    <br>

                                    <script>
                                        function order_edit(order_id) {
                                            console.log(encodeURIComponent(window.location.href));
                                            window.location.href = '/orders/' + order_id + '/edit' + '?source_html=' + encodeURIComponent(window.location.href);
                                        }
                                    </script>
                                    {{--                                <a class="btn btn-primary" href="{{route('customers.index')}}">客戶列表</a>--}}


                                    @if(!$order->code)
                                        <a class="btn btn-success" href="{{route('orders.get_code',$order->id)}}">送出訂單至業務系統</a>
                                    @endif


                                    <a class="btn btn-primary" onclick="order_edit({{$order->id}})">編輯</a>

                                    @if( Auth::user()->level==2)

                                        <form action="{{ route('orders.delete', $order->id) }}" method="post"
                                              style="display: inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('確定是否刪除')">刪除
                                            </button>
                                        </form>
                                    @endif

                                    <div align="right">
                                        <table>
                                            {{--                                            <tr>--}}
                                            {{--                                                <td>Subtotal</td>--}}
                                            {{--                                                <td class="text-right">{{$total_price}}</td>--}}
                                            {{--                                            </tr>--}}
                                            {{--                                            <tr style="border-bottom: 1px solid;">--}}
                                            {{--                                                <td>Discount</td>--}}
                                            {{--                                                <td class="text-right">{{round($order->discount)}}</td>--}}
                                            {{--                                            </tr>--}}
                                            <hr>
                                            <tr>
                                                <td>Total <br></td>
                                                <td class="text-right">:&nbsp &nbsp &nbsp
                                                    &nbsp{{$total_price-round($order->discount)}}</td>
                                            </tr>

                                        </table>


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
