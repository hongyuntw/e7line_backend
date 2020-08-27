@extends('layouts.master')

@section('title', '新增神腦商品')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                新增神腦商品
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 交易狀況</a></li>
                <li class="active">新增神腦商品</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            @if(session('msgs'))
                <div class="alert-danger alert text-center">
                    @foreach(session('msgs') as $msg)
                        {{$msg}} <br>

                    @endforeach
                </div>
        @endif

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">
                <input hidden id="input_detail_count" value="1">

                <form class="well form-horizontal" action="{{route('senao_products.store')}}" method="post"
                      id="product_form" >
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
                    <script>
                        function add_detail_field() {
                            var count = document.getElementById("input_detail_count").value;
                            count = parseInt(count);
                            count = count + 1;

                            html = '<div id="' + count + 'dynamic_field">';
                            html += '                            <div class="input-group">\n';
                            html += '                                <span class="input-group-addon"><i class="glyphicon glyphicon-shopping-cart"></i></span>';
                            html += '<input id="' + count + 'price" name="price[]" type="number" class="form-control"\n' +
                                '                                       placeholder="請輸入價錢">';
                            var product_array = '@json($product_array)';
                            product_array = JSON.parse(product_array);
                            html += '<select id="' + count + 'ISBN" name="ISBN[]" >';
                            html += '<option value="-1">請選擇商品</option>';
                            for (const [key, value] of Object.entries(product_array)) {
                                html+= '<option value="' + key + '"> ' + key + ' ' +  value + ' </option>'
                            }


                            html+= '</select>';


                            html += '</div>';
                            html += '</div>';

                            $("#dynamic_detail").append(html);
                            var select = $("#" + count + "ISBN").selectize();
                            select[0].selectize.setValue("-1");

                            document.getElementById("input_detail_count").value = count;
                        }

                        function delete_detail_field() {
                            var count = document.getElementById("input_detail_count").value;
                            count = parseInt(count);
                            var div = document.getElementById(count + "dynamic_field");
                            div.parentNode.removeChild(div);
                            document.getElementById("input_detail_count").value = count - 1;

                        }


                    </script>
                    <fieldset>
                        <div class="form-group">
                            <div class="col-md-6 inputGroupContainer">
                                <label class=" control-label">請輸入神腦料號</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <input id="senao_isbn" name="senao_isbn" class="form-control" type="text"
                                           placeholder="神腦料號" value="{{old('senao_isbn')}}">

                                </div>

                            </div>
                            <div class="col-md-6 inputGroupContainer">
                                <label class="control-label">商品資訊</label>
                                <a onclick="add_detail_field()"><i class="glyphicon glyphicon-plus-sign"></i></a>
                                <a onclick="delete_detail_field()"><i class="glyphicon glyphicon-minus-sign"></i></a>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <input id="1price" name="price[]" type="number" class="form-control"
                                           placeholder="請輸入價錢">
                                    <select id="1ISBN" name="ISBN[]">
                                        <option value="-1">請選擇商品</option>
                                        @foreach($product_array as $k=>$v)
                                            <option value="{{$k}}">{{$k . ' ' . $v}}</option>
                                        @endforeach
                                    </select>
                                    <script>
                                        var isbn_select = $("#1ISBN").selectize();
                                        isbn_select[0].selectize.setValue("-1");

                                    </script>
                                </div>

                                <div id="dynamic_detail">

                                </div>

                            </div>
                            <br>
                            <br>


                            {!! Form::hidden('redirect_to', old('redirect_to', URL::previous())) !!}


                        </div>
                        <div class="col-md-12 text-center">
                            <a class="btn btn-danger" href="{{ old('redirect_to', URL::previous())}}">取消</a>
                            <button type="submit" class="btn btn-primary">確認送出</button>
                        </div>


                    </fieldset>
                    <br>
                    <br>
                </form>
            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
