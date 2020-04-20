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
                                <div id="Customer">
                                    <h4 class="text-center">
                                        <label style="font-size: medium">Order Detail</label>
                                    </h4>
                                </div>

                                <table class="table table-striped" style="width: 100%">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 10px;">客戶名稱</th>
                                        <th class="text-center" style="width: 10px;">訂購窗口</th>
                                        <th class="text-center" style="width: 10px;">日期</th>
                                        <th class="text-center" style="width: 10px;">狀態</th>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td>{{$order->customer->name}}</td>
                                        <td>{{$order->business_concat_person->name}}</td>
                                        <td>{{$order->name}}</td>





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
                                <div id="ConcatWindow"></div>
                                <h4 class="text-center">
                                    <label style="font-size: medium">Order Note</label>
                                </h4>


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
                                        <label style="font-size: medium">Order items</label>
                                    </h4>
                                </div>


                                <table class="table table-striped" width="100%">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 15%;">Products</th>
                                        <th class="text-center" style="width: 25%;">Quantity</th>
                                        <th class="text-center" style="width: 25%;">Price</th>
                                        <th class="text-center" style="width: 10%;">Amount</th>

                                    </tr>
                                    </thead>
                                    @foreach ($concat_records as $concat_record)
                                        <tr class="text-center">

                                        </tr>
                                    @endforeach

                                </table>
                                <tfoot>
                                </tfoot>
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
