@extends('layouts.master')

@section('title', '編輯訂單')

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
                <li class="active">編輯訂單</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="row">
                <!-- .col -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">編輯訂單</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="{{ route('sales.update', $sale->id) }}" method="post">

                            @csrf
                            @method('PATCH')

                            <div class="box-body">

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h4><i class="icon fa fa-ban"></i> 錯誤！</h4>
                                        請修正以下表單錯誤：
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="title">訂單者姓名</label>
                                    <input type="text" class="form-control" id="title" name="order_name" placeholder="請輸入訂單姓名"
                                           value="{{ old('order_name', $sale->order_name) }}">
                                </div>
                                {{--<div class="form-group">--}}
                                {{--<label for="category">分類</label>--}}
                                {{--<select id="category" name="category_id" class="form-control">--}}
                                {{--@foreach($categories as $category)--}}
                                {{--<option value="{{ $category->id }}"{{ (old('category_id', $product->category_id) == $category->id)? ' selected' : '' }}>{{ $category->name }}</option>--}}
                                {{--@endforeach--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                <div class="form-group">
                                    <label for="order_phone">訂單電話</label>
                                    <input type="text" class="form-control" id="order_phone" name="order_phone" placeholder="請輸入電話"
                                           value="{{ old('order_phone', $sale->order_phone) }}">
                                </div>
                                <div class="form-group">
                                    <label for="order_address">訂單地址</label>
                                    <input type="text" class="form-control" id="order_address" name="order_address"
                                           placeholder="請輸入地址" value="{{ old('address', $sale->order_address) }}">
                                </div>
                                <div class="form-group">
                                    <label for="order_note">訂單註記</label>
                                    <textarea class="form-control" id="order_note" rows="5" name="order_note"
                                              placeholder="請輸入描述">{{ old('order_note', $sale->order_note) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="salesitem">訂單資料</label>
                                    @foreach($salesitems as $salesitem)
                                        <input type="text" class="form-control" id="salesitem" name="salesitem"
                                               disabled="true"
                                               value="{{ $salesitem->product->name }}">
                                        <input type="number" class="form-control" id="quantity" name="quantity" disabled="true"
                                               value="{{ old('quantity', $salesitem->quantity) }}">
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label for="cover">產品圖</label>
                                    <input type="file" id="cover">
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer text-right">
                                <a class="btn btn-link" href="#">取消</a>
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>
                        </form>
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
