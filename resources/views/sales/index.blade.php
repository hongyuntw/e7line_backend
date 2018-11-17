@extends('layouts.master')

@section('title', '訂單列表')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                訂單管理
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 訂單管理</a></li>
                <li class="active">訂單列表</li>
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
                            <h3 class="box-title">全站訂單一覽表</h3>

                            {{--<div class="box-tools">--}}
                            {{--<a class="btn btn-success btn-sm" href="{{ route('products.create') }}">新增訂單</a>--}}
                            {{--</div>--}}
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" style="width: 10px;">id</th>
                                    <th class="text-center" style="width: 70px">用戶名稱</th>
                                    <th class="text-center" style="width: 70px">訂單者姓名</th>
                                    <th class="text-center" style="width: 50px">訂單日期</th>
                                    <th class="text-center" style="width: 80px">訂單電話</th>
                                    <th class="text-center" style="width: 200px">訂單地址</th>
                                    <th class="text-center" style="width: 150px">訂單註記</th>
                                    <th class="text-center" style="width: 250px">詳細資訊</th>
                                    <th class="text-center" style="width: 50px">訂單金額</th>
                                    <th class="text-center" style="width: 120px">管理功能</th>
                                </tr>
                                @foreach ($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->id }}.</td>
                                        <td>{{ $sale->member->name }}</td>
                                        <td>{{ $sale->order_name}}</td>
                                        <td>{{ $sale->order_date }}</td>
                                        <td>{{ $sale->order_phone }}</td>
                                        <td>{{ $sale->order_address }}</td>
                                        <td>{{ $sale->order_note }}</td>
                                        <td>
                                            @foreach($sale->salesitem as $item)
                                                {{$item->product->name}} x {{$item->quantity}}
                                                <br>
                                            @endforeach
                                        </td>
                                        @php($totalprice=0)
                                        @foreach($sale->salesitem as $item)
                                            @php($totalprice+=$item->quantity*$item->sale_price)
                                        @endforeach
                                        <td>
                                            {{$totalprice}}元
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('sales.edit', $sale->id) }}"
                                               class="btn btn-xs btn-primary">編輯</a>
                                            <form action="{{ route('sales.destroy', $sale->id) }}" method="post"
                                                  style="display: inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">刪除</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li><a href="#">&laquo;</a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">&raquo;</a></li>
                            </ul>
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
