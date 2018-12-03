@extends('layouts.master')

@section('title', '編輯廣告')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                廣告管理
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 廣告管理</a></li>
                <li class="active">編輯廣告</li>
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
                            <h3 class="box-title">編輯廣告</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="{{ route('ads.update', $ad->id) }}" method="post" enctype="multipart/form-data">

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
                                    <label for="title">名稱</label>
                                    <input type="text" class="form-control" id="title" name="name" placeholder="請輸入名稱"
                                           value="{{ old('name', $ad->name) }}">
                                </div>

                                <div class="form-group">
                                    <label for="text_1">廣告文字敘述1 (MAX:20)</label>
                                    <input type="text" class="form-control" id="text_1" name="text_1" placeholder="請輸入文字"
                                           value="{{ old('text_1', $ad->text_1) }}">
                                </div>
                                    <div class="form-group">
                                        <label for="text_2">廣告文字敘述2 (MAX:20)</label>
                                        <input type="text" class="form-control" id="text_2" name="text_2" placeholder="請輸入文字"
                                               value="{{ old('text_2', $ad->text_2) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="text_3">廣告文字敘述3 (MAX:20)</label>
                                        <input type="text" class="form-control" id="text_3" name="text_3" placeholder="請輸入文字"
                                               value="{{ old('text_3', $ad->text_3) }}">
                                    </div>
                                <div class="form-group">
                                    <label for="cover">產品圖</label>
                                    <br>
                                    <img src="{{ '/storage/'.$ad->imagename }}" style="width:384px; height:134px;">
                                    <input type="file" id="image" name="image">
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer text-right">
                                <a class="btn btn-link" href="{{route('ads.index')}}">取消</a>
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
