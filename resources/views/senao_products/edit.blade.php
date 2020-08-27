@extends('layouts.master')

@section('title', '編輯神腦商品')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                編輯神腦商品
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><i class="fa fa-shopping-bag"></i> 交易狀況</li>
                <li class="active">編輯神腦商品</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">
                <fieldset>
                    <form class="well form-horizontal" action="{{route('senao_products.update',$isbn_relation->id)}}"  method="post"
                          id="product_form">
                        @csrf
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
                            <div class="col-md-3 inputGroupContainer">
                                <label class=" control-label">神腦料號</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <input disabled type="text" class="form-control" value="{{$isbn_relation->senao_product->senao_isbn}}">
                                    <input type="hidden" value="{{$source_html}}" name="source_html">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class=" control-label">ISBN 及 商品名稱</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <input disabled type="text" class="form-control" value="{{$isbn_relation->product_relation->ISBN}}">
                                    <input disabled type="text" class="form-control" value="{{$product_name}}">

                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">價錢</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input  type="number" class="form-control" name="price" id="price"
                                           placeholder="price"
                                           value="{{ old('price',$isbn_relation->price) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <a class="btn btn-danger" href="{{ URL::previous() }}">取消</a>
                            <button type="submit" class="btn btn-primary">更新</button>
                        </div>
                    </form>
                </fieldset>


            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
