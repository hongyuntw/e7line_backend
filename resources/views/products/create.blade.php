@extends('layouts.master')

@section('title', '新增商品')

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
                <li class="active">新增商品</li>
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
                            <h3 class="box-title">新增商品</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="title">名稱</label>
                                    <input type="text" class="form-control" id="title" placeholder="請輸入名稱">
                                </div>
                                <div class="form-group">
                                    <label for="category">分類</label>
                                    <select id="category" class="form-control">
                                        <option>分類一</option>
                                        <option>分類二</option>
                                        <option>分類三</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="price">價格</label>
                                    <input type="text" class="form-control" id="price" placeholder="請輸入價格">
                                </div>
                                <div class="form-group">
                                    <label for="unit">單位</label>
                                    <input type="text" class="form-control" id="unit" placeholder="請輸入單位">
                                </div>
                                <div class="form-group">
                                    <label for="description">描述</label>
                                    <textarea class="form-control" id="description" rows="5" placeholder="請輸入描述"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="cover">產品圖</label>
                                    <input type="file" id="cover">
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer text-right">
                                <a class="btn btn-link" href="#">取消</a>
                                <button type="submit" class="btn btn-primary">新增</button>
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
