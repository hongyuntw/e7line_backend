@extends('layouts.master')

@section('title', '編輯商品')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                編輯商品
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('orders.index')}}"><i class="fa fa-shopping-bag"></i> 交易狀況</a></li>
                <li class="active">編輯商品</li>
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
                    function product_change(product_select) {
                        // console.log(product_select);
                        // console.log(product_list_count);
                        var product_id = product_select.options[product_select.selectedIndex].value;
                        // console.log(product_id);
                        if (product_id > 0) {
                            $.ajax({
                                url: '/ajax/get_product_details',
                                data: {product_id: product_id}
                            })
                                .done(function (res) {
                                    console.log(res);
                                    var myNode;
                                    myNode = document.getElementById("product_select_div");
                                    // console.log(myNode);
                                    myNode.innerHTML = '';
                                    html = '<select class="form-control" name="product_detail_id" ';
                                    html += 'onchange="product_detail_change(this)">';
                                    html += '<option value=-1>請選擇產品</option>';
                                    for (let [key, value] of Object.entries(res)) {
                                        html += '<option value=\"' + key + '\">' + value[0] + '</option>'
                                    }
                                    html += '</select>';
                                    // console.log(myNode);
                                    myNode.innerHTML = html;
                                    var product_price_input;
                                    product_price_input = document.getElementById("price");
                                    product_price_input.value = '';

                                    var product_ISBN_input;
                                    product_ISBN_input = document.getElementById("ISBN");
                                    product_ISBN_input.value = '';


                                })

                        }
                    }

                    function product_detail_change(product_detail_select) {
                        var product_detail_id = product_detail_select.options[product_detail_select.selectedIndex].value;
                        var product_select = document.getElementById("product_select");
                        var product_id = product_select.options[product_select.selectedIndex].value;
                        if (product_detail_id > 0) {
                            $.ajax({
                                url: '/ajax/get_product_details_price',
                                data: {
                                    product_detail_id: product_detail_id,
                                    product_id: product_id,
                                }
                            })
                                .done(function (res) {
                                    console.log(res);
                                    var product_price_input;
                                    product_price_input = document.getElementById("price");
                                    product_price_input.value = res['price'];

                                    var product_ISBN_input;
                                    product_ISBN_input = document.getElementById("ISBN");
                                    if (res['ISBN']) {
                                        product_ISBN_input.value = res['ISBN'];
                                    }
                                })
                        }

                    }

                    function mySubmit(form) {
                        var result = function () {
                            var tmp = null;
                            $.ajax({
                                async: false,
                                type: "POST",
                                url: '{{route('products.update')}}',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                },
                                data: $("#product_form").serialize(),
                                success: function (msg) {
                                    if (msg.success) {
                                        alert('update success')
                                    } else {
                                        alert(msg.message);
                                    }
                                },
                            });
                            return false;
                        }();
                        return false;
                    }

                    function change_name(form) {
                        $.ajax({
                            async: false,
                            type: "POST",
                            url: '{{route('products.change_name')}}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                            },
                            data: $("#change_product_form").serialize(),
                            success: function (msg) {
                                console.log(msg);
                                alert(msg);
                            },
                        });



                        return false;
                    }


                </script>
                <fieldset>

                    <form class="well form-horizontal" action="" onsubmit="return mySubmit(this);" method="post"
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
                                <label class=" control-label">商品公司名</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <div id="dynamic_concat_person">
                                        <select onchange="product_change(this)" id="product_select" name="product_id">
                                            <option value="-1">請選擇商品</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                            @endforeach
                                        </select>
                                        <script>
                                            var select = $("#product_select").selectize();
                                            select[0].selectize.setValue("-1");
                                        </script>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class=" control-label">細項名稱</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                    <div id="product_select_div">
                                        <select class="form-control" onchange="product_detail_change(this)"
                                                id="product_detail_select" name="product_detail_id">
                                            <option selected value="-1">請選擇商品</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">價錢</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input type="number" class="form-control" name="price" id="price"
                                           placeholder="price"
                                           value="{{ old('price') }}">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">ISBN</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-align-justify"></i></span>
                                    <input type="text" class="form-control" name="ISBN" placeholder="ISBN" id="ISBN"
                                           value="{{ old('ISBN') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <a class="btn btn-danger" href="{{ URL::previous() }}">取消</a>
                            <button type="submit" class="btn btn-primary">更新</button>
                            <a class="btn btn-danger" onclick="deleteProduct()">刪除</a>
                        </div>
                    </form>
                    <script>
                        function deleteProduct(){
                            var product_select = document.getElementById("product_select");
                            var product_id = product_select.options[product_select.selectedIndex].value;
                            var product_detail_select = document.getElementById("product_select");
                            var product_detail_id = product_detail_select.options[product_detail_select.selectedIndex].value;


                            $.ajax({
                                async:false,
                                url: '{{route('products.delete')}}',
                                data: {product_id: product_id,product_detail_id:product_detail_id}
                            })
                                .done(function (res) {
                                    alert(res);
                                    window.location.reload();


                                })


                        }


                    </script>


{{--                    產品名字變更--}}
                    @if(Auth::user()->level==2)
                        <form class="well form-horizontal" action="" onsubmit="return change_name(this);" method="post"
                              id="change_product_form">
                            @csrf
                            <div class="form-group">
                                <div class="col-md-6 inputGroupContainer">
                                    <label class=" control-label">商品公司名</label>
                                    <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                        <select id="product_select2" name="product_id">
                                            <option value="-1">請選擇商品</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                            @endforeach
                                        </select>
                                        <script>
                                            var select = $("#product_select2").selectize();
                                            select[0].selectize.setValue("-1");
                                        </script>
                                        <input name="product_name" class="form-control" placeholder="請輸入想變更的名稱">
                                    </div>

                                </div>
                                <div class="col-md-6 inputGroupContainer">
                                    <label class=" control-label">細項名稱</label>
                                    <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-shopping-cart"></i></span>
                                        <select name="product_detail_id" id="product_detail_select2">
                                            <option selected value="-1">請選擇商品</option>
                                            @foreach($product_details as $product_detail)
                                                <option value="{{$product_detail->id}}">{{$product_detail->name}}</option>
                                            @endforeach
                                        </select>
                                        <input name="product_detail_name" class="form-control" placeholder="請輸入想變更的名稱">
                                        <script>
                                            var select = $("#product_detail_select2").selectize();
                                            select[0].selectize.setValue("-1");
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <a class="btn btn-danger" href="{{ URL::previous() }}">取消</a>
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>
                        </form>
                    @endif
                </fieldset>


            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
