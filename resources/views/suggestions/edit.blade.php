@extends('layouts.master')

@section('title', '回信')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                意見管理
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 意見管理</a></li>
                <li class="active">回信</li>
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
                            <h3 class="box-title">回信</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="title">客戶意見</label>
                                    <br>
                                    <textarea disabled>{{$suggestion->text}}</textarea>
                                </div>
                            </div>
                        </form>

                        <form role="form" action="{{ route('suggestions.reply', $suggestion->id) }}" method="post" enctype="multipart/form-data">
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
                                        <label for="reply">回覆內容</label>
                                        <textarea class="form-control" id="reply" rows="5" name="reply"
                                                  placeholder="請輸入回覆"></textarea>
                                    </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer text-right">
                                <a class="btn btn-link" href="{{route('suggestions.index')}}">取消</a>
                                <button type="submit" class="btn btn-primary">送出</button>
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
