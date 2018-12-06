@extends('layouts.master')

@section('title', '優惠券列表')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                優惠券管理
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 優惠券管理</a></li>
                <li class="active">優惠券列表</li>
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
                            <h3 class="box-title">全站優惠券一覽表</h3>

                            {{--<div class="box-tools">--}}
                            {{--<a class="btn btn-success btn-sm" href="{{ route('products.create') }}">新增優惠券</a>--}}
                            {{--</div>--}}
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    <th class="text-center" style="width: 10px;">id</th>
                                    <th class="text-center" style="width: 70px">優惠券代碼</th>
                                    <th class="text-center" style="width: 70px">使用狀況</th>
                                    <th class="text-center" style="width: 70px">折價種類</th>
                                    {{--<th class="text-center" style="width: 120px">管理功能</th>--}}
                                </tr>
                                @foreach ($coupons as $coupon)
                                    <tr class="text-center">
                                        <td>{{ $coupon->id }}.</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>
                                            <span
                                                class="{{  ($coupon->is_used==0) ? 'label label-success' : 'label label-warning'  }}">
                                                {{  ($coupon->is_used==0) ? '未使用' : '已使用'  }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($coupon->type==1)
                                                全部商品9折
                                            @elseif($coupon->type==2)
                                                全部商品8折
                                            @elseif($coupon->type==3)
                                                全部商品7折
                                            @endif
                                        </td>

                                        {{--<td class="text-center">--}}
                                        {{--<a href="{{ route('coupons.edit', $coupon->id) }}"--}}
                                        {{--class="btn btn-xs btn-primary">編輯</a>--}}
                                        {{--<form action="{{ route('coupons.destroy', $coupon->id) }}" method="post"--}}
                                        {{--style="display: inline-block">--}}
                                        {{--@csrf--}}
                                        {{--@method('DELETE')--}}
                                        {{--<button type="submit" class="btn btn-xs btn-danger">刪除</button>--}}
                                        {{--</form>--}}
                                        {{--</td>--}}
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $coupons->links()}}
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
