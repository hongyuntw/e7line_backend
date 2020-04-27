@extends('layouts.master')

@section('title', '新增訂單')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <style>

        .order-form .container {
            color: #4c4c4c;
            padding: 20px;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, .1);
        }

        .order-form-label {
            margin: 8px 0 0 0;
            font-size: 14px;
            font-weight: bold;
        }

        .order-form-input {
            width: 100%;
            padding: 8px 8px;
            border-width: 1px !important;
            border-style: solid !important;
            border-radius: 3px !important;
            font-family: 'Open Sans', sans-serif;
            font-size: 14px;
            font-weight: normal;
            font-style: normal;
            line-height: 1.2em;
            background-color: transparent;
            border-color: #cccccc;
        }

        .btn-submit:hover {
            background-color: #090909 !important;
        }
    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                新增訂單
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 交易管理</a></li>
                <li class="active">新增訂單</li>
            </ol>
        </section>


        <!-- Main content -->
        <section class="content container-fluid">


            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">
                <form class="well form-horizontal" action="{{route('orders.store')}}" method="post" id="contact_form">
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
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">公司名稱</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <select id="select_customer" name="customer_id">
                                        <option value="-1">Select a customer...</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>

{{--                                    ajax get customer concat person and control disabled field--}}
                                    <script>
                                        var customer_select_id;
                                        $('#select_customer').selectize({
                                            onChange: function (value) {
                                                customer_select_id = value;
                                                var other_customer_name_input = document.getElementById("other_customer_name");
                                                // console.log(other_customer_name_input);
                                                if (value < 0) {
                                                    other_customer_name_input.disabled = false;
                                                } else {
                                                    other_customer_name_input.disabled = true;
                                                }
                                                // console.log(customer_select_id);
                                                $.ajax({
                                                    url: '/ajax/get_customer_concat_persons',
                                                    data: {customer_select_id: customer_select_id}
                                                })
                                                    .done(function (res) {
                                                        console.log(res);
                                                        const myNode = document.getElementById("dynamic_concat_person");
                                                        myNode.innerHTML = '';
                                                        html = '<select id="concat_person_select" name="business_concat_person_id" class="form-control" onchange="concat_person_onchange()">';
                                                        html += '<option value=-1>請選擇福委</option>'
                                                        for (let [key, value] of Object.entries(res)) {
                                                            html += '<option value=\"' + key + '\">' + value + '</option>'
                                                        }
                                                        html += '</select>'
                                                        $('#dynamic_concat_person').append(html);
                                                    })
                                            }
                                        });

                                        function concat_person_onchange() {
                                            var concat_person_select = document.getElementById("concat_person_select");
                                            // console.log(concat_person_select);
                                            var selected_concat_person_id = concat_person_select.options[concat_person_select.selectedIndex].value;
                                            var other_concat_person_input = document.getElementById("other_concat_person_input");
                                            if (selected_concat_person_id < 0) {
                                                other_concat_person_input.disabled = false;
                                            } else {
                                                other_concat_person_input.disabled = true;

                                            }
                                        }
                                    </script>
                                </div>

                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">公司名稱</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="other_customer_name"
                                           name="other_customer_name" placeholder="非客戶名單之補充"
                                           value="{{ old('other_name') }}">
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">訂購目的</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <select name="welfare_id" class="form-control">
                                        @foreach($welfares as $welfare)
                                            <option value="{{$welfare->id}}">{{$welfare->welfare_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 inputGroupContainer">
                                <label class=" control-label">訂購窗口</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <div id="dynamic_concat_person">
                                        <select class="form-control">
                                            <option disabled value="-1">請選擇福利目的</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class=" control-label">訂購窗口</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" name="other_concat_person_name"
                                           placeholder="非名單內補充" id="other_concat_person_input"
                                           value="{{ old('other_concat_person_name') }}">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">電話</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                                    <input type="text" class="form-control" name="phone_number"
                                           placeholder="phone number"
                                           value="{{ old('phone_number') }}">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">信箱</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                    <input type="text" class="form-control" name="email" placeholder="email"
                                           value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">e7line帳號</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <select id="e7line_account" name="e7line_account" class="form-control">
                                        <option value="123@mail.com">輸入account</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">e7line姓名</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                    <input type="text" class="form-control" name="e7line_name" placeholder="e7line姓名"
                                           value="{{ old('e7line_name',"231") }}">
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">負責業務</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <select id="user_id" name="user_id" class="form-control">
                                        @foreach($users as $user)
                                            @if($user->is_left==0)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endif
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">狀態</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                    <select name="status" class="form-control">
                                        @foreach($status_names as $status_name)
                                            <option value="{{$loop->index}}">{{$status_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-8 inputGroupContainer">
                                <label class="control-label">收貨地址</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <input type="text" class="form-control" id="ship_to" name="ship_to"
                                           placeholder="請輸入收貨地址" value="{{ old('ship_to') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <script>
                                function payment_method_change(payment_method_select) {
                                    var id = payment_method_select.options[payment_method_select.selectedIndex].value;
                                    //貨到/匯款
                                    if(id == 0){
                                        document.getElementById("payment_account_name").disabled=false;
                                        document.getElementById("payment_account_num").disabled=false;
                                        document.getElementById("swift_code").disabled=false;
                                    }
                                    else{
                                        document.getElementById("payment_account_name").disabled=true;
                                        document.getElementById("payment_account_num").disabled=true;
                                        document.getElementById("swift_code").disabled=true;
                                    }

                                }
                            </script>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">付款方式</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <select name="payment_method" class="form-control" onchange="payment_method_change(this)">
                                        @foreach($payment_method_names as $payment_method_name)
                                            <option value="{{$loop->index}}">{{$payment_method_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">銀行代碼</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="swift_code" name="swift_code"
                                           placeholder="請輸入銀行代碼" value="{{ old('swift_code') }}">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">戶名</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="payment_account_name" name="payment_account_name"
                                           placeholder="請輸入戶名" value="{{ old('payment_account_name') }}">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">帳號</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="payment_account_num"  name="payment_account_num"
                                           placeholder="請輸入帳號" value="{{ old('payment_account_num') }}">
                                </div>
                            </div>


                        </div>
                        <div class="form-group">
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">付款日期</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="date" class="form-control" name="payment_date"
                                           value="{{ old('payment_date') }}">
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">收貨日期</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="date" class="form-control" name="receive_date"
                                           value="{{ old('receive_date') }}">
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">最遲到貨日期</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="date" class="form-control" name="latest_arrival_date"
                                           value="{{ old('latest_arrival_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">

                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">統編</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="tax_id" name="tax_id"
                                           placeholder="請輸入統編，一組以上請打在備註"
                                           value="{{ old('tax_id') }}">
                                </div>

                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">備註</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                    <textarea rows="3" class="form-control" id="note" name="note" placeholder="請輸入備註"
                                    >{{ old('note') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <input hidden id="product_list_count" value="1">

                        {{--                        dymaic get product info--}}
                        <script>
                            function product_change(product_select) {
                                console.log(product_select);
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
                                            if(product_select.id == "product"){
                                                myNode = document.getElementById("product_detail");
                                            }
                                            else{
                                                myNode = document.getElementById(product_select.id + "_detail");
                                            }
                                            console.log(myNode);
                                            myNode.innerHTML = '';
                                            html = '<select class="form-control" name="product_detail_id[]" onchange="product_detail_change(this)">';
                                            html += '<option value=-1>請選擇產品</option>';
                                            for (let [key, value] of Object.entries(res)) {
                                                html += '<option value=\"' + key + '\">' + value[0] + '</option>'
                                            }
                                            html += '</select>';
                                            // console.log(myNode);
                                            myNode.innerHTML=html;
                                        })

                                }
                            }

                            function product_detail_change(product_detail_select) {
                                var product_detail_id = product_detail_select.options[product_detail_select.selectedIndex].value;
                                //parent_node_id
                                var parent_node_id = product_detail_select.parentNode.id;
                                console.log(parent_node_id);
                                if (product_detail_id > 0) {
                                    $.ajax({
                                        url: '/ajax/get_product_details_price',
                                        data: {product_detail_id: product_detail_id}
                                    })
                                        .done(function (res) {
                                            console.log(res);
                                            var product_price_input;
                                            if(parent_node_id == "product_detail"){
                                                product_price_input = document.getElementById("product_detail_price");
                                            }
                                            else{
                                                product_price_input = document.getElementById(parent_node_id+"_price");
                                            }
                                            product_price_input.value = res;
                                        })

                                }

                            }
                            function add_product(){
                                var count = document.getElementById("product_list_count").value;
                                count = parseInt(count);
                                count = count + 1;
                                document.getElementById("product_list_count").value = count;
                                const myNode = document.getElementById("product_list");
                                html = '<div id="product_list'+count+'">' +
                                    '<a id="delete_product_list_btn" class="btn btn-link" onclick="delete_product('+count+')">\n' +
                                    '                            <i class="glyphicon glyphicon-minus-sign"></i>\n' +
                                    '                            delete product\n' +
                                    '                        </a>'+
                                    ' <div class="form-group">\n' +
                                    '                                <div class="col-md-3 inputGroupContainer">\n' +
                                    '                                    <label class="control-label">Product</label>\n' +
                                    '                                    <div class="input-group">\n' +
                                    '                                        <span class="input-group-addon"><i\n' +
                                    '                                                class="glyphicon glyphicon-shopping-cart"></i></span>\n' +
                                    '                                        <select name="product_id[]" class="form-control"\n' +
                                    'id="product'+count+'"'+
                                    '                                                onchange="product_change(this)">\n' +
                                    '                                            <option value="-1">選擇產品</option>\n' +
                                    '                                            @foreach($products as $product)\n' +
                                    '                                                <option value="{{$product->id}}">{{$product->name}}</option>\n' +
                                    '                                            @endforeach\n' +
                                    '                                        </select>\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="col-md-3 inputGroupContainer">\n' +
                                    '                                    <label class="control-label">Detail</label>\n' +
                                    '                                    <div class="input-group">\n' +
                                    '                                        <span class="input-group-addon"><i\n' +
                                    '                                                class="glyphicon glyphicon-shopping-cart"></i></span>\n' +
                                    '                                        <div id="product'+count+'_detail">\n' +
                                    '                                            <select class="form-control" name="product_detail_id[]">\n' +
                                    '                                                <option value="-1">選擇產品</option>\n' +
                                    '                                            </select>\n' +
                                    '                                        </div>\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="col-md-3 inputGroupContainer">\n' +
                                    '                                    <label class="control-label">Quantity</label>\n' +
                                    '                                    <div class="input-group">\n' +
                                    '                                        <span class="input-group-addon"><i\n' +
                                    '                                                class="glyphicon glyphicon-list"></i></span>\n' +
                                    '                                        <input type="number" class="form-control" name="quantity[]" placeholder="請輸入數量">\n' +
                                    '\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="col-md-3 inputGroupContainer">\n' +
                                    '                                    <label class="control-label">Price</label>\n' +
                                    '                                    <div class="input-group">\n' +
                                    '                                        <span class="input-group-addon"><i\n' +
                                    '                                                class="glyphicon glyphicon-list"></i></span>\n' +
                                    '                                        <input type="number" class="form-control" name="price[]" id="product'+count+'_detail_price">\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                            </div>'+
                                    '</div>';

                                $('#product_list').append(html);
                                var new_select_id = "#product"+count;
                                make_selectize(new_select_id);
                                // $("select[id=" + new_select_id + "]").selectize({
                                // });
                                // $("#product2").selectize({
                                //
                                // });
                            }

                            // function make_selectize(id){
                            //     // console.log(id);
                            //     $(id)[0].selectize({
                            //
                            //     });
                            //
                            //
                            // }


                            function delete_product(num) {
                                console.log(num);
                                var count = document.getElementById("product_list_count").value;
                                count = parseInt(count);
                                document.getElementById("product_list_count").value = count-1;
                                var id = "product_list"+num;
                                var node = document.getElementById(id);
                                node.remove();
                                // console.log(node);
                            }

                        </script>


                        <a id="add_product_btn" class="btn btn-link" onclick="add_product()">
                            <i class="glyphicon glyphicon-plus-sign"></i>
                            add product
                        </a>

                        <div id="product_list">
                            <div class="form-group">
                                <div class="col-md-3 inputGroupContainer">
                                    <label class="control-label">Product</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-shopping-cart"></i></span>
                                        <select id="product" name="product_id[]"
                                                onchange="product_change(this)">
                                            <option value="-1">選擇產品</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                            @endforeach
                                        </select>
                                        <script>
                                            $('#product').selectize({
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-md-3 inputGroupContainer">
                                    <label class="control-label">Detail</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-shopping-cart"></i></span>
                                        <div id="product_detail">
                                            <select class="form-control" name="product_detail_id[]">
                                                <option value="-1">選擇產品</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 inputGroupContainer">
                                    <label class="control-label">Quantity</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-list"></i></span>
                                        <input type="number" class="form-control" name="quantity[]" placeholder="請輸入數量">

                                    </div>
                                </div>
                                <div class="col-md-3 inputGroupContainer">
                                    <label class="control-label">Price</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-list"></i></span>
                                        <input type="number" class="form-control" name="price[]" id="product_detail_price">
                                    </div>
                                </div>
                            </div>
                        </div>



                    {!! Form::hidden('redirect_to', old('redirect_to', URL::previous())) !!}
                    <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <a class="btn btn-danger" href="{{ old('redirect_to', URL::previous())}}">取消</a>
                                <button type="submit" class="btn btn-primary">新增</button>
                            </div>
                        </div>

                    </fieldset>
                </form>
            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
