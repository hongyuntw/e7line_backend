@extends('layouts.master')

@section('title', '新增商品')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                新增商品
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 交易狀況</a></li>
                <li class="active">新增商品</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">
                <input hidden id="input_detail_count" value="1">
                <script>
                    function mySubmit(form) {
                        var result = function () {
                            var tmp = null;
                            $.ajax({
                                async: false,
                                type: "POST",
                                url: '{{route('products.validate_product_form')}}',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                },
                                data: $("#product_form").serialize(),
                                success: function (msg) {
                                    // console.log("success");
                                    tmp = true;
                                    // console.log(tmp);

                                },
                                error: function (data) {
                                    var errors = data.responseJSON;
                                    // console.log(errors);
                                    var msg = '';
                                    for (let [key, value] of Object.entries(errors.errors)) {
                                        msg += value;
                                        msg += '\n';
                                    }
                                    alert(msg);
                                    tmp = false;
                                    // console.log(tmp);
                                }
                            });
                            return tmp;
                        }();
                        console.log("test result is");
                        if (result) {
                            return true;
                        } else {
                            return false;

                        }
                    }

                </script>
                <form class="well form-horizontal" action="{{route('products.store')}}"
                      onsubmit="return mySubmit(this);" method="post" id="product_form">
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
                        function product_change(product_select) {
                            var product_id = product_select.options[product_select.selectedIndex].value;
                            if (product_id == -1) {
                                document.getElementById("new_product").disabled = false;
                            } else {
                                document.getElementById("new_product").disabled = true;

                            }

                        }

                        function product_detail_change(product_detail_select) {
                            var product_detail_value = product_detail_select.options[product_detail_select.selectedIndex].value;
                            var mapping_input_id = product_detail_select.id.replace('detail_select', '');
                            console.log(mapping_input_id);
                            if (product_detail_value == -1) {
                                document.getElementById(mapping_input_id).style.display = "";
                            } else {
                                document.getElementById(mapping_input_id).style.display = "none";

                            }

                        }

                        function add_detail_field() {
                            var count = document.getElementById("input_detail_count").value;
                            count = parseInt(count);
                            count = count + 1;
                            // html = '<input id="' + count + '" name="product_detail[]" type="text" class="form-control"\n' +
                            //     '                                       placeholder="請輸入細項">';

                            html = '<div id="' + count + 'dynamic_field">';
                            html += '                            <div class="input-group">\n';
                            html += '                                <span class="input-group-addon"><i class="glyphicon glyphicon-shopping-cart"></i></span>';
                            // html += '';
                            html += '<select id="' + count + 'detail_select" onchange="product_detail_change(this)" name="product_detail_id[]">\n' +
                                '                                    <option value="-1">新增項目(輸入在下方)</option>\n' +
                                '                                    @foreach($product_details as $product_detail)\n' +
                                '                                        <option value="{{$product_detail->id}}">{{$product_detail->name}}</option>\n' +
                                '                                    @endforeach\n' +
                                '                                </select>\n' +
                                '                                <input id="' + count + '" name="product_detail[]" type="text" class="form-control"\n' +
                                '                                       placeholder="請輸入細項">';
                            html += '<input id="' + count + 'price" name="price[]" type="number" class="form-control"\n' +
                                '                                       placeholder="請輸入價錢">';
                            html += '<input id="' + count + 'ISBN" name="ISBN[]" type="text" class="form-control"\n' +
                                '                                       placeholder="請輸入ISBN">';
                            html += '</div>';
                            html += '</div>';

                            $("#dynamic_detail").append(html);
                            var select = $("#" + count + "detail_select").selectize();
                            select[0].selectize.setValue("-1");
                            document.getElementById("input_detail_count").value = count;
                        }

                        function delete_detail_field() {
                            var count = document.getElementById("input_detail_count").value;
                            count = parseInt(count);
                            var div = document.getElementById(count + "dynamic_field");
                            div.parentNode.removeChild(div);
                            document.getElementById("input_detail_count").value = count - 1;
                            // var input = document.getElementById(count);
                            // input.parentNode.removeChild(input);
                            // var select = document.getElementById(count+"detail_select");
                            // console.log(select);
                            // select.parentNode.removeChild(select);
                        }


                    </script>
                    <fieldset>
                        <div class="form-group">
                            <div class="col-md-6 inputGroupContainer">
                                <label class=" control-label">請選擇分類</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <select id="product" name="product_id" onchange="product_change(this)">
                                        <option value="-1">無</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                    <input id="new_product" name="product_name" class="form-control" type="text"
                                           placeholder="若不在上面請新增" value="{{old('product_name')}}">
                                </div>

                            </div>
                            <div class="col-md-6 inputGroupContainer">
                                <label class="control-label">細項</label>
                                <a onclick="add_detail_field()"><i class="glyphicon glyphicon-plus-sign"></i></a>
                                <a onclick="delete_detail_field()"><i class="glyphicon glyphicon-minus-sign"></i></a>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <select id="1detail_select" onchange="product_detail_change(this)"
                                            name="product_detail_id[]">
                                        <option value="-1">新增項目(輸入在下方)</option>
                                        @foreach($product_details as $product_detail)
                                            <option value="{{$product_detail->id}}">{{$product_detail->name}}</option>
                                        @endforeach
                                    </select>
                                    <input id="1" name="product_detail[]" type="text" class="form-control"
                                           placeholder="請輸入細項">
                                    <input id="1price" name="price[]" type="number" class="form-control"
                                           placeholder="請輸入價錢">
                                    <input id="1ISBN" name="ISBN[]" type="text" class="form-control"
                                           placeholder="請輸入ISBN">
                                </div>

                                <div id="dynamic_detail">

                                </div>
                                <script>
                                    var product_select = $("#product").selectize();
                                    product_select[0].selectize.setValue("-1");
                                    var detail_select = $("#1detail_select").selectize();
                                    detail_select[0].selectize.setValue("-1");

                                </script>
                            </div>
                            <br>
                            <br>


                            {!! Form::hidden('redirect_to', old('redirect_to', URL::previous())) !!}


                        </div>
                        <div class="col-md-12 text-center">
                            {{--                                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;--}}
                            <a class="btn btn-danger" href="{{ old('redirect_to', URL::previous())}}">取消</a>
                            {{--                        <button onclick="add_product_validate()" type="button" class="btn-primary btn">確認送出</button>--}}
                            <button type="submit" class="btn btn-primary">確認送出</button>
                        </div>


                    </fieldset>
                    <br>
                    <br>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{--                    <div class="form-group float-right align-middle">--}}
                    {{--                        --}}{{--                                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;--}}
                    {{--                        <a class="btn btn-danger" href="{{ URL::previous() }}">取消</a>--}}
                    {{--                        <button onclick="add_product_validate()" type="button" class="btn-primary btn">確認送出</button>--}}
                    {{--                        <button type="submit" class="btn btn-primary">確認送出</button>--}}
                    {{--                    </div>--}}
                </form>
            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
