@extends('layouts.master')

@section('title', '編輯訂單')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                編輯訂單
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 交易管理</a></li>
                <li class="active">編輯訂單</li>
            </ol>
        </section>


        <!-- Main content -->
        <section class="content container-fluid">


            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">
                <form class="well form-horizontal" action="{{route('orders.update',$order->id)}}" method="post"
                      id="contact_form">
                    @csrf
                    @method('PATCH')


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
                                            <option value="{{$customer->id}}"
                                                    @if($order->customer)
                                                    @if($customer->id==$order->customer->id)
                                                    selected
                                                @endif
                                                @endif
                                            >{{$customer->name}}</option>
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
                                                        var old_id = '{{$order->business_concat_person->id}}';
                                                        html = '<select id="concat_person_select" name="business_concat_person_id" class="form-control" onchange="concat_person_onchange()">';
                                                        html += '<option value=-1>請選擇福委</option>'
                                                        for (let [key, value] of Object.entries(res)) {
                                                            html += '<option value=\"' + key;
                                                            if (key == old_id) {
                                                                html += 'selected';
                                                            }
                                                            html += '\">' + value + '</option>'
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
                                           @if($order->customer)
                                           disabled
                                           @endif
                                           value="{{ old('other_name',$order->other_customer_name) }}">
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">訂購目的</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <select name="welfare_id" class="form-control">
                                        @foreach($welfares as $welfare)
                                            <option @if($welfare->id==$order->welfare->id)selected
                                                    @endif value="{{$welfare->id}}">{{$welfare->welfare_name}}</option>
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
                                        <select id="concat_person_select" class="form-control"
                                                name="business_concat_person_id"
                                                onchange="concat_person_onchange()">
                                            <option @if(!$order->business_concat_person) selected @endif value="-1">
                                                請選擇福委
                                            </option>
                                            @if($order->customer)
                                                @foreach($order->customer->business_concat_persons as $concat_person)
                                                    <option
                                                        @if($order->business_concat_person)
                                                        @if($order->business_concat_person->id == $concat_person->id)
                                                        selected
                                                        @endif
                                                        @endif
                                                        value="{{$concat_person->id}}"> {{$concat_person->name}}</option>
                                                @endforeach
                                            @endif
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
                                           @if($order->business_concat_person)
                                           disabled
                                           @endif
                                           value="{{ old('other_concat_person_name',$order->other_concat_person_name) }}">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">電話</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                                    <input type="text" class="form-control" name="phone_number"
                                           placeholder="phone number"
                                           value="{{ old('phone_number',$order->phone_number) }}">
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">信箱</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                    <input type="text" class="form-control" name="email" placeholder="email"
                                           value="{{ old('email',$order->email) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">e7line帳號</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input type="text" class="form-control" name="e7line_account"
                                           placeholder="輸入account"
                                           value="{{ old('e7line_account',$order->e7line_account) }}">
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">e7line姓名</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                    <input type="text" class="form-control" name="e7line_name" placeholder="e7line姓名"
                                           value="{{ old('e7line_name',$order->e7line_name) }}">
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
                                                <option
                                                    @if($order->user->id==$user->id)
                                                    selected
                                                    @endif
                                                    value="{{$user->id}}">{{$user->name}}</option>
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
                                        @foreach($order_status_names as $status_name)
                                            <option @if($order->status==$loop->index) selected
                                                    @endif value="{{$loop->index}}">{{$status_name}}</option>
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
                                           placeholder="請輸入收貨地址" value="{{ old('ship_to',$order->ship_to) }}">
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
                                            <option @if($order->payment_method==$loop->index) selected
                                                    @endif value="{{$loop->index}}">{{$payment_method_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">匯款帳戶</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <select @if($order->payment_method!=0) disabled @endif class="form-control" name="payment_account" id="payment_account">
                                        <option @if($order->payment_account=="公司帳戶") selected @endif value="公司帳戶">公司帳戶</option>
                                        <option @if($order->payment_account=="業務帳戶") selected @endif value="業務帳戶">業務帳戶</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class="control-label">後五碼</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input @if($order->payment_method!=0) disabled @endif type="text" class="form-control" id="last_five_nums"
                                           name="last_five_nums"
                                           placeholder="請輸入後五碼"
                                           value="{{ old('last_five_nums',$order->last_five_nums) }}">
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
                                           @if($order->payment_date)
                                           @php($payment_date = date("Y-m-d", strtotime($order->payment_date)))
                                           @else
                                           @php($payment_date = null)
                                           @endif
                                           value="{{ old('payment_date',$payment_date) }}">
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">收貨日期</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="date" class="form-control" name="receive_date"
                                           @if($order->receive_date)
                                           @php($receive_date = date("Y-m-d", strtotime($order->receive_date)))
                                           @else
                                           @php($receive_date = null)
                                           @endif
                                           value="{{ old('receive_date',$receive_date) }}">
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">最遲到貨日期</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="date" class="form-control" name="latest_arrival_date"
                                           @if($order->latest_arrival_date)
                                           @php($latest_arrival_date = date("Y-m-d", strtotime($order->latest_arrival_date)))
                                           @else
                                           @php($latest_arrival_date = null)
                                           @endif
                                           value="{{ old('latest_arrival_date',$latest_arrival_date) }}">
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
                                           value="{{ old('tax_id',$order->tax_id) }}">
                                </div>

                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">備註</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                    <textarea rows="3" class="form-control" id="note" name="note" placeholder="請輸入備註"
                                    >{{ old('note',$order->note) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <input hidden id="product_list_count" value="{{count($order_items)}}">

                        {{--                                                                    dymaic get product info--}}
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
                                            html = '<select class="form-control" name="product_detail_id[]" onchange="product_detail_change(this)">';
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
                                console.log(parent_node_id);
                                if (product_detail_id > 0) {
                                    $.ajax({
                                        url: '/ajax/get_product_details_price',
                                        data: {product_detail_id: product_detail_id}
                                    })
                                        .done(function (res) {
                                            console.log(res);
                                            var product_price_input;
                                            if (parent_node_id == "product_detail") {
                                                product_price_input = document.getElementById("product_detail_price");
                                            } else {
                                                product_price_input = document.getElementById(parent_node_id + "_price");
                                            }
                                            product_price_input.value = res;
                                            computeSum();

                                        })

                                }

                            }

                            function add_product() {
                                var count = document.getElementById("product_list_count").value;
                                count = parseInt(count);
                                count = count + 1;
                                // console.log(count);
                                document.getElementById("product_list_count").value = count;
                                const myNode = document.getElementById("product" + count + "_list");
                                html = '<div id="product_list' + count + '">' +
                                    '<a id="delete_product_list_btn" class="btn btn-link" onclick="delete_product(' + count + ')">\n' +
                                    '                            <i class="glyphicon glyphicon-minus-sign"></i>\n' +
                                    '                            \n' +
                                    '                        </a>' +
                                    ' <div class="form-group">\n' +
                                    '                                <div class="col-md-3 inputGroupContainer">\n' +
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
                                    '                                <div class="col-md-3 inputGroupContainer">\n' +
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
                                    '                                <div class="col-md-3 inputGroupContainer">\n' +
                                    '                                    <label class="control-label">Quantity</label>\n' +
                                    '                                    <div class="input-group">\n' +
                                    '                                        <span class="input-group-addon"><i\n' +
                                    '                                                class="glyphicon glyphicon-list"></i></span>\n' +
                                    '                                        <input type="number" class="form-control" name="quantity[]" placeholder="請輸入數量" onchange="computeSum()">\n' +
                                    '\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="col-md-3 inputGroupContainer">\n' +
                                    '                                    <label class="control-label">Price</label>\n' +
                                    '                                    <div class="input-group">\n' +
                                    '                                        <span class="input-group-addon"><i\n' +
                                    '                                                class="glyphicon glyphicon-list"></i></span>\n' +
                                    '                                        <input type="number" onchange="computeSum()" class="form-control" name="price[]" id="product' + count + '_detail_price">\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                            </div>' +
                                    '</div>';

                                $('#product_list').append(html);
                                var new_select_id = "#product" + count;
                                make_selectize(new_select_id);
                                computeSum();

                            }

                            function make_selectize(id) {
                                // console.log(id);
                                $(id).selectize({});


                            }


                            function delete_product(num) {
                                console.log(num);
                                var count = document.getElementById("product_list_count").value;
                                count = parseInt(count);
                                console.log(count);
                                document.getElementById("product_list_count").value = count - 1;
                                var id = "product_list" + num;
                                console.log(id);
                                var node = document.getElementById(id);
                                node.remove();
                                // console.log(node);
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
                                $("#total_price").text(totalPirce);
                            }
                            $(document).ready(function(){
                                computeSum();
                            });

                        </script>


                        <a id="add_product_btn" class="btn btn-link" onclick="add_product()">
                            <i class="glyphicon glyphicon-plus-sign"></i>
                            add product
                        </a>

                        @foreach($order_items as $order_item)
                            @if($loop->index==0)
                                @php($product_var = "product")

                            @else
                                @php($product_var="product".(string)(($loop->index)+1))
                                {{--                                {{dd($product_var)}}--}}

                            @endif
                            @if($loop->index==0)
                                <div id="product_list">
                                    @else
                                        <div id="product_list{{$loop->index+1}}">
                                            <a class="btn btn-link" onclick="delete_product({{$loop->index+1}})">
                                                <i class="glyphicon glyphicon-minus-sign"></i>
                                            </a>
                                            @endif

                                            <div class="form-group">
                                                <div class="col-md-3 inputGroupContainer">
                                                    <label class="control-label">Product</label>
                                                    <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-shopping-cart"></i></span>
                                                        <select id="{{$product_var}}" name="product_id[]"
                                                                onchange="product_change(this)">
                                                            <option value="-1">選擇產品</option>
                                                            @foreach($products as $product)
                                                                <option
                                                                    @if($product->id==$order_item->product_relation->product->id) selected
                                                                    @endif value="{{$product->id}}">{{$product->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <script>
                                                            $('#' + '{{$product_var}}').selectize({});
                                                        </script>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 inputGroupContainer">
                                                    <label class="control-label">Detail</label>
                                                    <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-shopping-cart"></i></span>
                                                        <div id="{{$product_var}}_detail">
                                                            <select class="form-control" name="product_detail_id[]">
                                                                <option value="-1">選擇產品</option>
                                                                @foreach($product_relations as $product_relation)
                                                                    @if($product_relation->product_id == $order_item->product_relation->product->id)
                                                                        <option
                                                                            @if($product_relation->product_detail_id==$order_item->product_relation->product_detail->id) selected
                                                                            @endif
                                                                            value="{{$product_relation->product_detail_id}}">{{$product_relation->product_detail->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 inputGroupContainer">
                                                    <label class="control-label">Quantity</label>
                                                    <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-list"></i></span>
                                                        <input type="number" class="form-control" name="quantity[]" onchange="computeSum()"
                                                               placeholder="請輸入數量"
                                                               value="{{old('quantity['.($loop->index+1).']',$order_item->quantity)}}">

                                                    </div>
                                                </div>
                                                <div class="col-md-3 inputGroupContainer">
                                                    <label class="control-label">Price</label>
                                                    <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-list"></i></span>
                                                        <input type="number" class="form-control" name="price[]" onchange="computeSum()"
                                                               value="{{old('price['.($loop->index+1).']',$order_item->price)}}"
                                                               id="{{$product_var}}_detail_price">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div align="right">
                                            <table>
                                                {{--                                            <tr>--}}
                                                {{--                                                <td>Subtotal</td>--}}
                                                {{--                                                <td class="text-right">{{$total_price}}</td>--}}
                                                {{--                                            </tr>--}}
                                                {{--                                            <tr style="border-bottom: 1px solid;">--}}
                                                {{--                                                <td>Discount</td>--}}
                                                {{--                                                <td class="text-right">{{round($order->discount)}}</td>--}}
                                                {{--                                            </tr>--}}
                                                <hr>
                                                <tr>
                                                    <td>Total <br></td>
                                                    <td class="text-right">:&nbsp &nbsp &nbsp
                                                        &nbsp <span id="total_price"> 0 </span></td>
                                                </tr>

                                            </table>


                                        </div>
                                        <input hidden name="source_html" value="{{$source_html}}">

                                    {!! Form::hidden('redirect_to', old('redirect_to', URL::previous())) !!}
                                    <!-- Button -->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"></label>
                                            <div class="col-md-4">
                                                <a class="btn btn-danger"
                                                   href="{{ old('redirect_to', URL::previous())}}">取消</a>
                                                <button type="submit" class="btn btn-primary">更新</button>
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
