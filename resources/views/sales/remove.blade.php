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
                            <a href="{{route('sales.showup')}}">處理中訂單</a>
                            <a href="{{route('sales.showremove')}}">已結單訂單</a>
                            <a href="{{route('sales.index')}}">全部訂單</a>

                            {{--<div class="box-tools">--}}
                            {{--<a class="btn btn-success btn-sm" href="{{ route('products.create') }}">新增訂單</a>--}}
                            {{--</div>--}}
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" style="width: 10px;">訂單編號</th>
                                    <th class="text-center" style="width: 70px">訂單日期</th>
                                    <th class="text-center" style="width: 70px">顧客名稱</th>
                                    <th class="text-center" style="width: 70px">訂單金額</th>
                                    <th class="text-center" style="width: 50px">訂單狀態</th>
                                    <th class="text-center" style="width: 120px">管理功能</th>
                                </tr>
                                @foreach ($sales as $sale)
                                    <tr class="text-center">

                                        <td>{{ $sale->id }}.</td>
                                        <td>{{ $sale->order_date }}</td>
                                        <td>{{ $sale->order_name }}</td>
                                        @php($totalprice=0)
                                        @foreach($sale->salesitems as $item)
                                            @php($totalprice+=$item->quantity*$item->sale_price)
                                        @endforeach
                                        <td>{{$totalprice}}元</td>
                                        <td>
                                            <span class="{{  ($sale->shipment==0) ? 'label label-warning' : 'label label-success'  }}">
                                                {{  ($sale->shipment==0) ? '處理中' : '已完成'  }}
                                            </span>
                                        </td>
                                        <td>
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
                            {{ $sales->links()}}
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
