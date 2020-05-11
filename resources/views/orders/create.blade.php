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
            <script>
                {{--                do validate--}}
                function mySubmit() {
                    var result = function () {
                        var tmp = null;
                        $.ajax({
                            async: false,
                            type: "POST",
                            url: '{{route('orders.validate_order_form')}}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                            },
                            data: $("#order_form").serialize(),
                            success: function (msg) {
                                console.log(msg)
                                tmp = true;
                            },
                            error: function (data) {
                                console.log(data);
                                var errors = data.responseJSON;
                                var msg = '';
                                for (let [key, value] of Object.entries(errors.errors)) {
                                    msg += value;
                                    msg += '\n';
                                }
                                alert(msg);
                                tmp = false;
                            }
                        });
                        return tmp;
                    }();
                    // console.log("test result is");
                    // console.log(result);
                    return result;
                }

                //create order but stay in this page
                function createOrder() {
                    var result = mySubmit();
                    if (result) {
                        $.ajax({
                            async: false,
                            type: "POST",
                            url: '{{route('orders.store')}}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                            },
                            data: $("#order_form").serialize(),
                            success: function (msg) {
                                // console.log("success");
                                tmp = true;
                                // console.log(tmp);
                                alert("新增訂單成功")
                                // $("#order_dynamic_field").load(" #order_dynamic_field > *");
                                var node = document.getElementById("product");
                                node.parentNode.innerHTML = '';
                                $("#order_dynamic_field").load(location.href + " #order_dynamic_field");
                                console.log("reload finish");
                                // console.log($('#product'));
                                setTimeout(function () {
                                    $("#product").selectize();
                                }, 1000);


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
                    }
                    // console.log("after ajax");
                    // $("#product").selectize();

                }

            </script>


            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            {{--            <div class="container">--}}
            <form class=" form-horizontal" action="{{route('orders.store')}}" method="post" id="order_form"
                  onsubmit="return mySubmit();">
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
                    <h4>訂購基本資訊</h4>
                    <div class="container well">

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
                                    <input type="text" class="form-control" id="other_customer_name"
                                           name="other_customer_name" placeholder="非客戶名單之補充"
                                           value="{{ old('other_name') }}">


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
                                                        // console.log(myNode);
                                                        myNode.innerHTML = '';
                                                        html = '<select id="concat_person_select" name="business_concat_person_id" class="form-control" onchange="concat_person_onchange()">';
                                                        html += '<option value=-1>請選擇福委</option>'
                                                        for (let [key, value] of Object.entries(res)) {
                                                            html += '<option value=\"' + key + '\">' + value + '</option>';
                                                        }
                                                        html += '</select>';
                                                        html += '<input type="text" class="form-control" name="other_concat_person_name"\n' +
                                                            '                                               placeholder="非名單內補充" id="other_concat_person_input"\n' +
                                                            '                                               value="{{ old("other_concat_person_name") }}">';
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
                            <div class="col-md-3 inputGroupContainer">
                                <label class=" control-label">訂購窗口</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <div id="dynamic_concat_person">
                                        <select class="form-control" name="business_concat_person_id">
                                            <option value="-1">選擇窗口</option>
                                        </select>
                                        <input type="text" class="form-control" name="other_concat_person_name"
                                               placeholder="非名單內補充" id="other_concat_person_input"
                                               value="{{ old('other_concat_person_name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 inputGroupContainer">
                                <label class="control-label">e7line帳號</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <div id="e7line_field">
                                        <input style="display: none" value="" name="e7line_account"
                                               id="e7line_account" class="form-control">
                                        <input style="display: none" type="text" class="form-control" name="e7line_name" id="e7line_name"
                                               placeholder="e7line姓名"
                                               value="">
                                    </div>
                                    <button type="button" onclick="gete7lineAccount()" style="color: #00a65a"
                                            class="form-control">Get Account
                                    </button>
                                </div>
                            </div>
                            {{--                            api to get account --}}
                            <script>
                                function e7line_info_change(select){
                                    var str = select.options[select.selectedIndex].value;
                                    var text = str.split("###");
                                    console.log(text);
                                    document.getElementById("e7line_account").value=text[2];
                                    document.getElementById("e7line_name").value=text[0];

                                }
                                function gete7lineAccount() {
                                    var customer_info;
                                    //    有可能是從選單選的
                                    var customer_select = document.getElementById("select_customer");
                                    var customer_select_text = customer_select.options[customer_select.selectedIndex].text;
                                    var customer_select_val = customer_select.options[customer_select.selectedIndex].value;
                                    if (customer_select_val != -1) {
                                        customer_info = customer_select_text;
                                    } else {
                                        //從其他輸入選擇
                                        var other_customer_name_val = document.getElementById("other_customer_name").value;
                                        if (other_customer_name_val == null || other_customer_name_val == "") {
                                            alert('需要提供客戶資訊，請選擇客戶或輸入名字');
                                            return;
                                        }
                                        customer_info = other_customer_name_val;
                                    }
                                    $.ajax({
                                        async: false,
                                        type: "POST",
                                        url: '{{route('orders.get_e7line_account_info')}}',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                        },
                                        data: {
                                            customer_info: customer_info
                                        },
                                        success: function (data) {
                                            // console.log(data);
                                            var data = JSON.parse(data);
                                            // console.log(data);
                                            var node = document.getElementById("e7line_field");
                                            node.innerHTML = "";
                                            html = '<select id="e7line_info" name="e7line_info" onchange="e7line_info_change(this)">';
                                            if (data.isScuess) {
                                                if(data.members.length==0){
                                                    alert("找不到對應之客戶，請重新輸入關鍵字");
                                                    return;
                                                }

                                                for (let [key, value] of Object.entries(data.members)) {
                                                    // console.log(key);
                                                    // console.log(value);
                                                    var val = value.Name+'###'+ value.companyName+'###'+value.memberNo;
                                                    var display_val = value.Name+'-'+ value.companyName+'-'+value.memberNo;
                                                    html+= '<option value="'+val+ '">'+ display_val +'</option>';
                                                    // $("#e7line_field").append(html);
                                                }
                                                html += '</select>';
                                                html += '<input style="display: none" value="'+ data.members[0].memberNo+'" name="e7line_account"\n' +
                                                    '                                               id="e7line_account" class="form-control">\n' +
                                                    '                                        <input  style="display: none" type="text" class="form-control" name="e7line_name" id="e7line_name"\n' +
                                                    '                                               placeholder="e7line姓名"\n' +
                                                    '                                               value="'+ data.members[0].Name +'">';
                                                $("#e7line_field").append(html);

                                            }
                                            else {
                                                alert(data.message);
                                            }
                                        },
                                        error: function () {
                                            alert('伺服器出了點問題，稍後再重試');
                                        }
                                    });
                                    $("#e7line_info").selectize();


                                }
                            </script>


                        </div>

                        <div class="form-group">

                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">電話</label>
                                <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-earphone"></i></span>
                                    <input type="text" class="form-control" name="phone_number"
                                           placeholder="phone number"
                                           value="{{ old('phone_number') }}">
                                </div>
                            </div>

                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">信箱</label>
                                <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-envelope"></i></span>
                                    <input type="text" class="form-control" name="email" placeholder="email"
                                           value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
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
                    </div>
                </fieldset>
                <fieldset>
                    <h4>收貨/付款資訊</h4>
                    <div class="container well">

                        <!-- Text input-->
                        <div class="form-group">

                            <div class="col-md-6 inputGroupContainer">
                                <label class="control-label">收貨地址</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <input type="text" class="form-control" id="ship_to" name="ship_to"
                                           placeholder="請輸入收貨地址" value="{{ old('ship_to') }}">
                                </div>
                            </div>
                            <div class="col-md-6 inputGroupContainer">
                                <label class=" control-label">收貨日期</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="date" class="form-control" name="receive_date"
                                           value="{{ old('receive_date') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <script>
                                function payment_method_change(payment_method_select) {
                                    var id = payment_method_select.options[payment_method_select.selectedIndex].value;
                                    //貨到/匯款
                                    if (id == 0) {
                                        document.getElementById("payment_account").disabled = false;
                                        document.getElementById("last_five_nums").disabled = false;
                                        // document.getElementById("swift_code").disabled = false;
                                    } else {
                                        document.getElementById("payment_account").disabled = true;
                                        document.getElementById("last_five_nums").disabled = true;
                                        // document.getElementById("swift_code").disabled = true;
                                    }

                                }
                            </script>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">付款方式</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <select name="payment_method" class="form-control"
                                            onchange="payment_method_change(this)">
                                        @foreach($payment_method_names as $payment_method_name)
                                            <option value="{{$loop->index}}">{{$payment_method_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">匯款帳戶</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <select class="form-control" name="payment_account" id="payment_account">
                                        <option value="公司帳戶">公司帳戶</option>
                                        <option value="業務帳戶">業務帳戶</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">後五碼</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text"
                                           class="form-control" id="last_five_nums"
                                           name="last_five_nums"
                                           placeholder="請輸入後五碼"
                                           value="{{ old('last_five_nums') }}">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class=" control-label">付款日期</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="date" class="form-control" name="payment_date"
                                           value="{{ old('payment_date') }}">
                                </div>
                            </div>
                            {{--                            <div class="col-md-3 inputGroupContainer">--}}
                            {{--                                <label class="control-label">後五碼</label>--}}
                            {{--                                <div class="input-group">--}}
                            {{--                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>--}}
                            {{--                                    <input type="text" class="form-control" id="last_five_nums"--}}
                            {{--                                           name="last_five_nums"--}}
                            {{--                                           placeholder="請輸入後五碼" value="{{ old('last_five_nums') }}">--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}


                        </div>
                    </div>

                </fieldset>


                <fieldset>
                    <h4>訂購資訊</h4>

                    <div class="container well">
                        <div id="order_dynamic_field">
                            <div class="form-group">

                                <div class="col-md-4 inputGroupContainer">
                                    <label class=" control-label">統編及抬頭</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input type="text" class="form-control" id="tax_id" name="tax_id"
                                               placeholder="請輸入統編"
                                               value="{{ old('tax_id') }}">
                                        <input type="text" class="form-control" id="title"
                                               name="title" placeholder="若為現金卷請輸入抬頭"
                                               value="{{ old('title') }}">
                                    </div>


                                </div>
                                <div class="col-md-4 inputGroupContainer">
                                    <label class="control-label">備註</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-pencil"></i></span>
                                        <textarea rows="3" class="form-control" id="note" name="note"
                                                  placeholder="請輸入備註"
                                        >{{ old('note') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 inputGroupContainer">
                                    <label class="control-label">運費</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                        <input type="number" class="form-control" id="shipping_fee"
                                               name="shipping_fee" onchange="computeSum()"
                                               placeholder="請輸入運費" value="{{ old('shipping_fee',0) }}">
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
                                                if (product_select.id == "product") {
                                                    myNode = document.getElementById("product_detail");
                                                } else {
                                                    myNode = document.getElementById(product_select.id + "_detail");
                                                }
                                                console.log(myNode);
                                                myNode.innerHTML = '';
                                                html = '<select class="form-control" id="' + product_select.id + '_detail_select" name="product_detail_id[]" onchange="product_detail_change(this)">';
                                                html += '<option value=-1>請選擇產品</option>';
                                                for (let [key, value] of Object.entries(res)) {
                                                    html += '<option value=\"' + key + '\">' + value[0] + '</option>'
                                                }
                                                html += '</select>';
                                                // console.log(myNode);
                                                myNode.innerHTML = html;
                                            })

                                    }
                                }

                                function product_detail_change(product_detail_select) {
                                    var product_detail_id = product_detail_select.options[product_detail_select.selectedIndex].value;
                                    //parent_node_id
                                    var parent_node_id = product_detail_select.parentNode.id;
                                    var product_select_id = product_detail_select.id.replace('_detail_select', '');
                                    var product_select = document.getElementById(product_select_id);

                                    var product_id = product_select.options[product_select.selectedIndex].value;

                                    // console.log(parent_node_id);
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
                                                if (parent_node_id == "product_detail") {
                                                    product_price_input = document.getElementById("product_detail_price");
                                                } else {
                                                    product_price_input = document.getElementById(parent_node_id + "_price");
                                                }
                                                product_price_input.value = res['price'];

                                                var product_ISBN_input;
                                                if (parent_node_id == "product_detail") {
                                                    product_ISBN_input = document.getElementById("product_detail_ISBN");
                                                } else {
                                                    product_ISBN_input = document.getElementById(parent_node_id + "_ISBN");
                                                }
                                                if (res['ISBN']) {
                                                    product_ISBN_input.value = res['ISBN'];
                                                }

                                                computeSum();

                                            })

                                    }

                                }

                                function add_product() {
                                    var count = document.getElementById("product_list_count").value;
                                    count = parseInt(count);
                                    count = count + 1;
                                    document.getElementById("product_list_count").value = count;
                                    const myNode = document.getElementById("product_list");
                                    html = '<div id="product_list' + count + '">' +
                                        '<a id="delete_product_list_btn" class="btn btn-link" onclick="delete_product(' + count + ')">\n' +
                                        '                            <i class="glyphicon glyphicon-minus-sign"></i>\n' +
                                        '                            delete product\n' +
                                        '                        </a>' +
                                        ' <div class="form-group">\n' +
                                        '                                <div class="col-md-2 inputGroupContainer">\n' +
                                        '                                    <label class="control-label">Product</label>\n' +
                                        '                                    <div class="input-group">\n' +
                                        '                                        <span class="input-group-addon"><i\n' +
                                        '                                                class="glyphicon glyphicon-shopping-cart"></i></span>\n' +
                                        '                                        <select name="product_id[]" \n' +
                                        'id="product' + count + '"' +
                                        '                                                onchange="product_change(this)">\n' +
                                        '                                            <option value="-1">選擇產品</option>\n' +
                                        '                                            @foreach($products as $product)\n' +
                                        '                                                <option value="{{$product->id}}">{{$product->name}}</option>\n' +
                                        '                                            @endforeach\n' +
                                        '                                        </select>\n' +
                                        '                                    </div>\n' +
                                        '                                </div>\n' +
                                        '                                <div class="col-md-2 inputGroupContainer">\n' +
                                        '                                    <label class="control-label">Detail</label>\n' +
                                        '                                    <div class="input-group">\n' +
                                        '                                        <span class="input-group-addon"><i\n' +
                                        '                                                class="glyphicon glyphicon-shopping-cart"></i></span>\n' +
                                        '                                        <div id="product' + count + '_detail">\n' +
                                        '                                            <select class="form-control" name="product_detail_id[]">\n' +
                                        '                                                <option value="-1">選擇產品</option>\n' +
                                        '                                            </select>\n' +
                                        '                                        </div>\n' +
                                        '                                    </div>\n' +
                                        '                                </div>\n' +
                                        '<div class="col-md-2 inputGroupContainer">\n' +
                                        '                                    <label class="control-label">ISBN</label>\n' +
                                        '                                    <div class="input-group">\n' +
                                        '                                        <span class="input-group-addon"><i\n' +
                                        '                                                class="glyphicon glyphicon-list"></i></span>\n' +
                                        '                                        <input type="text" class="form-control" name="ISBN[]" \n' +
                                        '                                               id="product' + count + '_detail_ISBN">\n' +
                                        '                                    </div>\n' +
                                        '                                </div>' +
                                        '                                <div class="col-md-2 inputGroupContainer">\n' +
                                        '                                    <label class="control-label">Quantity</label>\n' +
                                        '                                    <div class="input-group">\n' +
                                        '                                        <span class="input-group-addon"><i\n' +
                                        '                                                class="glyphicon glyphicon-list"></i></span>\n' +
                                        '                                        <input type="number" class="form-control" name="quantity[]" placeholder="請輸入數量" onchange="computeSum()">\n' +
                                        '\n' +
                                        '                                    </div>\n' +
                                        '                                </div>\n' +
                                        '                                <div class="col-md-2 inputGroupContainer">\n' +
                                        '                                    <label class="control-label">Price</label>\n' +
                                        '                                    <div class="input-group">\n' +
                                        '                                        <span class="input-group-addon"><i\n' +
                                        '                                                class="glyphicon glyphicon-list"></i></span>\n' +
                                        '                                        <input type="number" onchange="computeSum()" class="form-control" name="price[]" id="product' + count + '_detail_price">\n' +
                                        '                                    </div>\n' +
                                        '                                </div>\n' +
                                        '<div class="col-md-2 inputGroupContainer">\n' +
                                        '                                    <label class="control-label">規格</label>\n' +
                                        '                                    <div class="input-group">\n' +
                                        '                                        <span class="input-group-addon"><i\n' +
                                        '                                                class="glyphicon glyphicon-list"></i></span>\n' +
                                        '                                        <input type="text" class="form-control" name="spec_name[]">\n' +
                                        '                                    </div>\n' +
                                        '                                </div>' +
                                        '                            </div>' +
                                        '</div>';

                                    $("#product_list").append(html);

                                    var new_select_id = "#product" + count;
                                    make_selectize(new_select_id);
                                    computeSum();
                                    // var element = document.getElementById("order_dynamic_field");
                                    // element.scrollTop = element.scrollHeight;
                                    window.scrollTo({
                                        top: document.body.scrollHeight - document.body.scrollHeight * 0.1,
                                        behavior: "smooth",
                                    });

                                    // $("product_list").scrollTop = $("product_list").scrollHeight;

                                }

                                function make_selectize(id) {
                                    console.log(id);
                                    $(id).selectize({});
                                    $("#product").selectize();
                                }


                                function delete_product(num) {
                                    console.log(num);
                                    // var count = document.getElementById("product_list_count").value;
                                    // count = parseInt(count);
                                    // document.getElementById("product_list_count").value = count - 1;
                                    var id = "product_list" + num;
                                    var node = document.getElementById(id);
                                    node.remove();
                                    computeSum();
                                }


                                function computeSum() {
                                    var totalPirce = 0;
                                    var input_all_qty = $('input[name="quantity[]"]');
                                    var input_all_price = $('input[name="price[]"]');
                                    for (var i = 0; i < input_all_qty.length; i++) {
                                        if (input_all_qty[i].value) {
                                            if (input_all_price[i].value) {
                                                // console.log(input_all_qty[i].value);
                                                // console.log(input_all_price[i].value);
                                                totalPirce += input_all_qty[i].value * input_all_price[i].value;
                                            }
                                        }
                                    }
                                    $("#subtotal_price").text(totalPirce)
                                    if ($("#shipping_fee").val()) {
                                        totalPirce += parseInt($("#shipping_fee").val());
                                        $("#shipping_fee_table").text($("#shipping_fee").val());
                                    } else {
                                        $("#shipping_fee_table").text(0);

                                    }
                                    $("#total_price").text(totalPirce);
                                }


                            </script>


                            <a id="add_product_btn" class="btn btn-link" onclick="add_product()">
                                <i class="glyphicon glyphicon-plus-sign"></i>
                                add product
                            </a>

                            <div id="product_list">
                                <div class="form-group">
                                    <div class="col-md-2 inputGroupContainer">
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
                                                $('#product').selectize({});
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-md-2 inputGroupContainer">
                                        <label class="control-label">Detail</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-shopping-cart"></i></span>
                                            <div id="product_detail">
                                                <select class="form-control" name="product_detail_id[]"
                                                        id="product_detail_select">
                                                    <option value="-1">選擇產品</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 inputGroupContainer">
                                        <label class="control-label">ISBN</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-list"></i></span>
                                            <input type="text" class="form-control" name="ISBN[]"
                                                   id="product_detail_ISBN">
                                        </div>
                                    </div>
                                    <div class="col-md-2 inputGroupContainer">
                                        <label class="control-label">Quantity</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-list"></i></span>
                                            <input type="number" class="form-control" name="quantity[]"
                                                   placeholder="請輸入數量"
                                                   onchange="computeSum()">

                                        </div>
                                    </div>
                                    <div class="col-md-2 inputGroupContainer">
                                        <label class="control-label">Price</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-list"></i></span>
                                            <input type="number" class="form-control" name="price[]"
                                                   onchange="computeSum()"
                                                   id="product_detail_price">
                                        </div>
                                    </div>
                                    <div class="col-md-2 inputGroupContainer">
                                        <label class="control-label">規格</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-list"></i></span>
                                            <input type="text" class="form-control" name="spec_name[]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div align="right">
                                <table>
                                    <tr>
                                        <td>運費</td>
                                        <td class="text-right"><span id="shipping_fee_table">0</span></td>
                                    </tr>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="text-right"><span id="subtotal_price">0</span></td>
                                        <hr>

                                    </tr>
                                    <tr>
                                        <td>Total <br></td>
                                        <td class="text-right">:&nbsp &nbsp &nbsp
                                            &nbsp <span id="total_price"> 0 </span></td>
                                    </tr>

                                </table>


                            </div>


                        {!! Form::hidden('redirect_to', old('redirect_to', URL::previous())) !!}
                        <!-- Button -->
                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <a class="btn btn-danger" href="{{ old('redirect_to', URL::previous())}}">取消</a>
                                    <button type="button" class="btn btn-success" onclick="createOrder()">新增(使用相同資訊)
                                    </button>
                                    <button type="submit" class="btn btn-primary">新增</button>
                                </div>
                            </div>
                        </div>

                </fieldset>
            </form>

            {{--            </div>--}}


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
