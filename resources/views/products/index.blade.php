@extends('layouts.master')

@section('title', '商品列表')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            商品管理
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-shopping-bag"></i> 商品管理</a></li>
            <li class="active">商品列表</li>
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
                        <h3 class="box-title">全站商品一覽表</h3>

                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{ route('products.create') }}">新增商品</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-center" style="width: 10px;">#</th>
                                <th class="text-center">名稱</th>
                                <th class="text-center" style="width: 250px">分類</th>
                                <th class="text-center" style="width: 120px">價格</th>
                                <th class="text-center" style="width: 120px">管理功能</th>
                            </tr>
                            @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}.</td>
                                <td>{{ $product->name }}</td>
                                <td>商品分類</td>
                                <td>{{ $product->price }} 元/{{ $product->unit }}</td>
                                <td class="text-center">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-xs btn-primary">編輯</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="post" style="display: inline-block">
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
