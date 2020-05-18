@extends('layouts.master')

@section('title', '新增報價參考')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                新增報價參考
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('quote.index')}}"><i class="fa fa-shopping-bag"></i> 報價參考</a></li>
                <li class="active">新增報價參考</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">
                <form class="well form-horizontal" action="{{route('quote.store')}}" method="post">
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
                    <fieldset>
                        <div class="form-group">
                            <div class="col-md-6 inputGroupContainer">
                                <label class=" control-label">請選擇產品</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <select id="product" name="product_id">
                                        <option value="-1">無</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                    <script>
                                        var product_select = $("#product").selectize();
                                        product_select[0].selectize.setValue("-1");
                                    </script>
                                </div>

                            </div>
                            <div class="col-md-6 inputGroupContainer">
                                <label class="control-label">級距</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <input name="step" class="form-control" type="text" id="step"
                                           placeholder="請輸入級距" value="{{old('step')}}">

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 inputGroupContainer">
                                <label class=" control-label">原廠</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <input id="origin" name="origin" class="form-control" type="text"
                                           placeholder="請輸入原廠" value="{{old('origin')}}">
                                </div>

                            </div>
                            <div class="col-md-6 inputGroupContainer">
                                <label class="control-label">e7Line</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <input id="e7line" name="e7line" type="text" class="form-control"
                                           placeholder="請輸入e7line" value="{{old('e7line')}}">
                                </div>
                            </div>
                            <div class="col-md-12 inputGroupContainer">
                                <label class="control-label">備註</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <input id="note" name="note" type="text" class="form-control"
                                           placeholder="請輸入備註" value="{{old('note')}}" >
                                </div>
                            </div>
                        </div>
                        {!! Form::hidden('redirect_to', old('redirect_to', URL::previous())) !!}
                        <div class="col-md-12 text-center">
                            <a class="btn btn-danger" href="{{ old('redirect_to', URL::previous())}}">取消</a>
                            <button type="submit" class="btn btn-primary">確認送出</button>
                        </div>

                    </fieldset>

                </form>
            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
