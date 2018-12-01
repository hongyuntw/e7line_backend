@extends('layouts.master')

@section('title', '新增管理者')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                管理者
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 管理者</a></li>
                <li class="active">新增管理者</li>
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
                            <h3 class="box-title">新增管理者</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">

                            @csrf

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
                                    <label for="title">名稱</label>
                                    <input type="text" class="form-control" id="title" name="name" placeholder="請輸入名稱"
                                           value="{{ old('name') }}">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                           placeholder="請輸入email" value="{{ old('email') }}">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="請輸入密碼" value="">
                                </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Password confirmation</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                                               placeholder="請輸入密碼" value="">
                                    </div>
                                {{--<div class="form-group">--}}
                                    {{--<label for="unit">Password</label>--}}
                                    {{--<input type="text" class="form-control" id="unit" name="unit" placeholder="請輸入單位"--}}
                                           {{--value="{{ old('unit') }}">--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="description">描述</label>--}}
                                    {{--<textarea class="form-control" id="description" name="description" rows="5"--}}
                                              {{--placeholder="請輸入描述">{{ old('description') }}</textarea>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="cover">產品圖</label>--}}
                                    {{--<input type="file" id="image" name="image">--}}
                                {{--</div>--}}
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
