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
                <li><a href="{{route('orders.index')}}"><i class="fa fa-shopping-bag"></i> 交易管理</a></li>
                <li class="active">編輯訂單</li>
            </ol>
        </section>


        <!-- Main content -->
        <section class="content container-fluid">


            <script>
                {{--                狀態處理中不能更改--}}

                $(document).ready(function () {
                    computeSum();
                    //
                    if ('{{$order->status!=0&&\Illuminate\Support\Facades\Auth::user()->level==0}}') {
                        console.log("hi bro");
                        console.log($('#base_info').find('input, textarea, button, select'));
                        $('#base_info').find('input, textarea, button, select').prop('disabled', true);
                        $('#item_info').find('input, textarea, button, select').prop('disabled', true);
                        $('#select_customer')[0].selectize.disable();


                    }
                    var title_node = document.getElementById("title");
                    var tax_id_node = document.getElementById("tax_id");
                    var note_node = document.getElementById("note");

                    if('{{$order->code == null || \Illuminate\Support\Facades\Auth::user()->level==2 }}'){
                        title_node.disabled = false;
                        tax_id_node.disabled = false;
                        note_node.disabled  = false;

                    }
                    else{
                        title_node.disabled = true;
                        tax_id_node.disabled = true;
                        note_node.disabled  = true;

                    }


                })

            </script>

            <!--------------------------
                      | Your Page Content Here |
                      -------------------------->
            <script>

                {{--                do validate--}}
                function mySubmit(form) {
                    var isbn_nodes = document.getElementsByName("ISBN[]");
                    var spec_nodes = document.getElementsByName("spec_name[]");
                    for(var i=0;i<isbn_nodes.length;i++){
                        var equal_flag = false;
                        for(var k = i-1 ; k>=0 ; k--){
                            if(spec_nodes[i].value == spec_nodes[k].value){
                                if(isbn_nodes[i].value == isbn_nodes[k].value ){
                                    equal_flag = true;
                                    break;
                                }
                            }
                        }
                        if(equal_flag){
                            alert('ISBN及規格不能有完全一樣的商品喔！');
                            return false;
                        }
                    }





                    $('#base_info').find('input, textarea, button, select').prop('disabled', false);
                    $('#item_info').find('input, textarea, button, select').prop('disabled', false);
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
                    // console.log("test result is");
                    if (!result) {
                        if ('{{$order->status!=0&&\Illuminate\Support\Facades\Auth::user()->level==0}}') {
                            $('#base_info').find('input, textarea, button, select').prop('disabled', true);
                            $('#item_info').find('input, textarea, button, select').prop('disabled', true);
                        }
                    }
                    return result;
                }


            </script>
            <form class="form-horizontal" action="{{route('orders.update',$order->id)}}" method="post"
                  id="order_form" onsubmit="return mySubmit(this);">
                @csrf
                {{--                @method('PATCH')--}}


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
                        <div id="base_info">
                            <div class="form-group">
                                <div class="col-md-4 inputGroupContainer">
                                    <label class=" control-label">公司名稱</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <select id="select_customer" name="customer_id"
                                                @if($order->status!=0 && \Illuminate\Support\Facades\Auth::user()->level==0) disabled @endif>
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
                                        <input type="text" class="form-control" id="other_customer_name"
                                               name="other_customer_name" placeholder="非客戶名單之補充"
                                               onchange="otherCustomerInputChange(this)"
                                               @if($order->customer)
                                               disabled
                                               @endif
                                               value="{{ old('other_name',$order->other_customer_name) }}">


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
                                                        var customer_select = document.getElementById("select_customer");
                                                        var customer_name = customer_select.options[customer_select.selectedIndex].text;
                                                        console.log(customer_name);
                                                        var node = document.getElementById("e7line_customer_info");
                                                        node.value = customer_name;
                                                        other_customer_name_input.disabled = true;
                                                        other_customer_name_input.value = null;
                                                    }
                                                    $.ajax({
                                                        url: '/ajax/get_customer_info',
                                                        data: {
                                                            customer_select_id: customer_select_id,
                                                        }

                                                    })
                                                        .done(function (res) {
                                                            var node = document.getElementById("ship_to");
                                                            node.value = res.address;
                                                            node = document.getElementById("phone_number")
                                                            node.value = res.phone_number;

                                                        });
                                                    $.ajax({
                                                        url: '/ajax/get_customer_concat_persons',
                                                        data: {customer_select_id: customer_select_id}
                                                    })
                                                        .done(function (res) {
                                                            console.log(res);
                                                            var concat_person_select = document.getElementById("concat_person_select");
                                                            console.log(concat_person_select);
                                                            var old_id = concat_person_select.options[concat_person_select.selectedIndex].value;
                                                            console.log(old_id);

                                                            const myNode = document.getElementById("dynamic_concat_person");
                                                            myNode.innerHTML = '';

                                                            html = '<select id="concat_person_select" name="business_concat_person_id" class="form-control" onchange="concat_person_onchange()">';
                                                            html += '<option value=-1>請選擇福委</option>'
                                                            for (let [key, value] of Object.entries(res)) {
                                                                html += '<option value=\"' + key;
                                                                if (key == old_id) {
                                                                    html += 'selected';
                                                                }
                                                                html += '\">' + value + '</option>'
                                                            }
                                                            html += '</select>';
                                                            html += '<input type="text" class="form-control" name="other_concat_person_name"\n' +
                                                                '                                                   placeholder="非名單內補充" id="other_concat_person_input"\n' +
                                                                '                                                   value="{{ old("other_concat_person_name",$order->other_concat_person_name) }}">';
                                                            $('#dynamic_concat_person').append(html);

                                                        })
                                                }
                                            });

                                            function concat_person_onchange() {
                                                var concat_person_select = document.getElementById("concat_person_select");
                                                // console.log(concat_person_select);
                                                var selected_concat_person_id = concat_person_select.options[concat_person_select.selectedIndex].value;
                                                var other_concat_person_input = document.getElementById("other_concat_person_input");
                                                if (selected_concat_person_id <= 0) {
                                                    other_concat_person_input.disabled = false;
                                                }
                                                else {
                                                    other_concat_person_input.disabled = true;
                                                    other_concat_person_input.value = "";

                                                }

                                                $.ajax({
                                                    url: '/ajax/get_concat_person_info',
                                                    data: {
                                                        selected_concat_person_id: selected_concat_person_id,
                                                    }

                                                })
                                                    .done(function (res) {
                                                        var node = document.getElementById("email");
                                                        node.value = res.email;
                                                    });
                                            }

                                            function otherCustomerInputChange(_input) {
                                                var node = document.getElementById("e7line_customer_info");
                                                node.value = _input.value;

                                            }
                                        </script>
                                    </div>

                                </div>

                                <div class="col-md-4 inputGroupContainer">
                                    <label class=" control-label">訂購窗口</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <div id="dynamic_concat_person">
                                            <select id="concat_person_select" class="form-control"
                                                    name="business_concat_person_id"
                                                    onchange="concat_person_onchange()">
                                                <option @if($order->business_concat_person == null) selected
                                                        @endif value="-1">
                                                    請選擇福委
                                                </option>
                                                @if($order->customer)
                                                    @foreach($order->customer->business_concat_persons as $concat_person)
                                                        <option
                                                            @if($order->business_concat_person!=null)
                                                            @if($order->business_concat_person->id == $concat_person->id)
                                                            selected
                                                            @endif
                                                            @endif
                                                            value="{{$concat_person->id}}"> {{$concat_person->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <input type="text" class="form-control" name="other_concat_person_name"
                                                   placeholder="非名單內補充" id="other_concat_person_input"
                                                   @if($order->business_concat_person)
                                                   disabled
                                                   @endif
                                                   value="{{ old('other_concat_person_name',$order->other_concat_person_name) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 inputGroupContainer">
                                    <label class="control-label">e7line帳號</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input class="form-control" id="e7line_customer_info"
                                               name="e7line_customer_info" placeholder="請輸入客戶資訊以供查詢">
                                        <input value="{{old('e7line_account',$order->e7line_account)}}"
                                               name="e7line_account"
                                               id="e7line_account" class="form-control" placeholder="e7line帳號">
                                        <input type="text" class="form-control" name="e7line_name" id="e7line_name"
                                               placeholder="e7line姓名"
                                               value="{{old('e7line_name',$order->e7line_name)}}">
                                        <div id="e7line_field">

                                        </div>
                                        <button type="button" onclick="gete7lineAccount()" style="color: #00a65a"
                                                class="form-control">Get Account
                                        </button>
                                    </div>
                                </div>
                                {{--                            api to get account --}}
                                <script>
                                    var customer_select = document.getElementById("select_customer");
                                    var initCustomerId = customer_select.options[customer_select.selectedIndex].value;
                                    var e7lineInfoInput = document.getElementById("e7line_customer_info");
                                    if (initCustomerId < 0) {
                                        e7lineInfoInput.value = document.getElementById("other_customer_name").value
                                    } else {
                                        e7lineInfoInput.value = customer_select.options[customer_select.selectedIndex].text;
                                    }

                                    function e7line_info_change(select) {
                                        var str = select.options[select.selectedIndex].value;
                                        var text = str.split("###");
                                        console.log(text);
                                        document.getElementById("e7line_account").value = text[2];
                                        document.getElementById("e7line_name").value = text[0];

                                    }

                                    function gete7lineAccount() {
                                        var customer_info;

                                        var e7lineInfoInput = document.getElementById("e7line_customer_info");
                                        customer_info = e7lineInfoInput.value;
                                        if (customer_info == null || customer_info == '') {
                                            alert('需要提供客戶資訊，請選擇客戶或輸入名字');
                                            return;
                                        }
                                        customer_info.replace('台', '臺');
                                        var default_value = null;

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
                                                    if (data.members.length == 0) {
                                                        alert("找不到對應之客戶，請重新輸入關鍵字");
                                                        return;
                                                    }

                                                    for (let [key, value] of Object.entries(data.members)) {
                                                        // console.log(key);
                                                        // console.log(value);
                                                        var val = value.Name + '###' + value.companyName + '###' + value.memberNo;
                                                        var display_val = value.Name + '-' + value.companyName + '-' + value.memberNo;
                                                        var display_val = value.Name + '-' + value.companyName + '-' + value.memberNo;
                                                        if (key == 0) {
                                                            default_value = val;
                                                        }

                                                        html += '<option value="' + val + '">' + display_val + '</option>';
                                                        // $("#e7line_field").append(html);
                                                    }
                                                    html += '</select>';
                                                    // html += '<input  value="' + data.members[0].memberNo + '" name="e7line_account"\n' +
                                                    //     '                                               id="e7line_account" class="form-control">\n' +
                                                    //     '                                        <input  type="text" class="form-control" name="e7line_name" id="e7line_name"\n' +
                                                    //     '                                               placeholder="e7line姓名"\n' +
                                                    //     '                                               value="' + data.members[0].Name + '">';
                                                    $("#e7line_field").append(html);

                                                } else {
                                                    alert(data.message);
                                                    return;
                                                }
                                            },
                                            error: function () {
                                                alert('伺服器出了點問題，稍後再重試');
                                                returm;
                                            }
                                        });
                                        var select = $("#e7line_info").selectize();
                                        select[0].selectize.setValue(default_value);


                                    }
                                </script>


                            </div>

                            <div class="form-group">
                                <div class="col-md-3 inputGroupContainer">
                                    <label class="control-label">電話</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-earphone"></i></span>
                                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                                               placeholder="phone number"
                                               value="{{ old('phone_number',$order->phone_number) }}">
                                    </div>
                                </div>
                                <div class="col-md-3 inputGroupContainer">
                                    <label class="control-label">信箱</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-envelope"></i></span>
                                        <input type="text" class="form-control" name="email" placeholder="email" id="email"
                                               value="{{ old('email',$order->email) }}">
                                    </div>
                                </div>
                                <div class="col-md-3 inputGroupContainer">
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
                        </div>
                    </div>
                </fieldset>


                <!-- Text input-->
                <fieldset>
                    <h4>收貨/付款資訊</h4>
                    <div class="container well">
                        <div id="pay_info">
                            <!-- Text input-->
                            <div class="form-group">
                                <div class="col-md-6 inputGroupContainer">
                                    <label class="control-label">收貨地址</label>
                                    <div class="input-group">
                                            <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-home"></i></span>
                                        <input type="text" class="form-control" id="ship_to" name="ship_to"
                                               placeholder="請輸入收貨地址" value="{{ old('ship_to',$order->ship_to) }}">
                                    </div>
                                </div>
                                <div class="col-md-6 inputGroupContainer">
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
                                        }

                                    }
                                </script>
                                <div class="col-md-3 inputGroupContainer">
                                    <label class="control-label">付款方式</label>
                                    <div class="input-group">
                                            <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-lock"></i></span>
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
                                            <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-lock"></i></span>
                                        <select @if($order->payment_method!=0) disabled @endif class="form-control"
                                                name="payment_account" id="payment_account">
                                            <option @if($order->payment_account=="公司帳戶") selected
                                                    @endif value="公司帳戶">
                                                公司帳戶
                                            </option>
                                            <option @if($order->payment_account=="業務帳戶") selected
                                                    @endif value="業務帳戶">
                                                業務帳戶
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 inputGroupContainer">
                                    <label class="control-label">後五碼</label>
                                    <div class="input-group">
                                            <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-lock"></i></span>
                                        <input @if($order->payment_method!=0) disabled @endif type="text"
                                               class="form-control" id="last_five_nums"
                                               name="last_five_nums"
                                               placeholder="請輸入後五碼"
                                               value="{{ old('last_five_nums',$order->last_five_nums) }}">
                                    </div>
                                </div>
                                <div class="col-md-3 inputGroupContainer">
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
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <h4>訂購資訊</h4>
                    <div class="container well">
                        <div id="item_info">
                            <div class="form-group">

                                <div class="col-md-3 inputGroupContainer">
                                    <label class=" control-label">統編及抬頭</label>
                                    <div class="input-group">
                                            <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-lock"></i></span>
                                        <input type="text" class="form-control" id="tax_id" name="tax_id"
                                               placeholder="請輸入統編"
                                               value="{{ old('tax_id',$order->tax_id) }}">
                                        <input type="text" class="form-control" id="title"
                                               name="title" placeholder="若為現金卷請輸入抬頭"
                                               value="{{ old('title',$order->title) }}">
                                    </div>
                                </div>
                                <div class="col-md-4 inputGroupContainer">
                                    <label class="control-label">備註</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i
                                                class="glyphicon glyphicon-pencil"></i></span>
                                        <textarea rows="3" class="form-control" id="note" name="note"
                                                  placeholder="請輸入備註"
                                        >{{ old('note',$order->note) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 inputGroupContainer">
                                    <label class="control-label">運費</label>
                                    <div class="input-group">
                                            <span class="input-group-addon"><i
                                                    class="glyphicon glyphicon-usd"></i></span>
                                        <input type="number" class="form-control" id="shipping_fee"
                                               name="shipping_fee"
                                               placeholder="請輸入運費" onchange="computeSum()"
                                               value="{{ old('shipping_fee',$order->shipping_fee) }}">
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
                                    var productListId = "#product" + count + "_list";
                                    count = count + 1;
                                    // console.log(count);
                                    document.getElementById("product_list_count").value = count;
                                    const myNode = document.getElementById("product" + count + "_list");
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

                                    $('#insertField').append(html);
                                    // $(productListId).prepend(html);
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
                                    var id = "product_list" + num;
                                    // console.log(id);
                                    var node = document.getElementById(id);
                                    node.remove();
                                    // console.log(node);
                                    computeSum();

                                    if(num==1){
                                        var node  = document.getElementById("first_delete_node");
                                        node.remove();
                                    }
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

                                // $(document).ready(function () {
                                // });

                            </script>


                            @if($order->status==0||\Illuminate\Support\Facades\Auth::user()->level!=0)
                                <a id="add_product_btn" class="btn btn-link" onclick="add_product()">
                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                    add product
                                </a>
                                <a id="first_delete_node" class="btn btn-link" onclick="delete_product(1);">
                                    <i class="glyphicon glyphicon-minus-sign"></i>
                                    delete product
                                </a>
                            @endif

                            @foreach($order_items as $order_item)
                                @if($loop->index==0)
                                    @php($product_var = "product")

                                @else
                                    @php($product_var="product".(string)(($loop->index)+1))
                                    {{--                                {{dd($product_var)}}--}}

                                @endif
                                @if($loop->index==0)
                                        <div id="insertField"></div>
                                    <div id="product_list1">


                                        @else
                                            <div id="product_list{{$loop->index+1}}">
                                                @if($order->status==0||\Illuminate\Support\Facades\Auth::user()->level!=0)
                                                    <a class="btn btn-link"
                                                       onclick="delete_product({{$loop->index+1}})">
                                                        <i class="glyphicon glyphicon-minus-sign"></i>delete product
                                                    </a>
                                                @endif


                                                @endif

                                                <div class="form-group">
                                                    <div class="col-md-2 inputGroupContainer">
                                                        <label class="control-label">Product</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-shopping-cart"></i></span>
                                                            <select id="{{$product_var}}" name="product_id[]"
                                                                    onchange="product_change(this)"
                                                                    @if($order->status!=0&&\Illuminate\Support\Facades\Auth::user()->level==0) disabled @endif>
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
                                                    <div class="col-md-2 inputGroupContainer">
                                                        <label class="control-label">Detail</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-shopping-cart"></i></span>
                                                            <div id="{{$product_var}}_detail">
                                                                <select class="form-control"
                                                                        name="product_detail_id[]"
                                                                        id="{{$product_var}}_detail_select"
                                                                        onchange="product_detail_change(this)"
                                                                        @if($order->status!=0&&\Illuminate\Support\Facades\Auth::user()->level==0) disabled @endif>
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
                                                    <div class="col-md-2 inputGroupContainer">
                                                        <label class="control-label">ISBN</label>
                                                        <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <input type="text" class="form-control" name="ISBN[]"

                                                                   id="{{$product_var}}_detail_ISBN"
                                                                   value="{{($order_item->product_relation->ISBN)}}"
                                                                   @if($order->status!=0&&\Illuminate\Support\Facades\Auth::user()->level==0) disabled @endif>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 inputGroupContainer">
                                                        <label class="control-label">Quantity</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-list"></i></span>
                                                            <input type="number" class="form-control"
                                                                   name="quantity[]"
                                                                   onchange="computeSum()"
                                                                   placeholder="請輸入數量"
                                                                   value="{{old('quantity['.($loop->index+1).']',$order_item->quantity)}}"
                                                                   @if($order->status!=0&&\Illuminate\Support\Facades\Auth::user()->level==0) disabled @endif>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 inputGroupContainer">
                                                        <label class="control-label">Price</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i
                                                                    class="glyphicon glyphicon-list"></i></span>
                                                            <input type="number" class="form-control" name="price[]"
                                                                   onchange="computeSum()"
                                                                   value="{{old('price['.($loop->index+1).']',$order_item->price)}}"
                                                                   id="{{$product_var}}_detail_price"
                                                                   @if($order->status!=0&&\Illuminate\Support\Facades\Auth::user()->level==0) disabled @endif>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 inputGroupContainer">
                                                        <label class="control-label">規格</label>
                                                        <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-list"></i></span>
                                                            <input type="text" class="form-control"
                                                                   name="spec_name[]"
                                                                   value="{{old('spec_name['.($loop->index+1).']',$order_item->spec_name)}}"
                                                                   @if($order->status!=0&&\Illuminate\Support\Facades\Auth::user()->level==0) disabled @endif>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                            @endforeach
                                            <div align="right">
                                                <table>
                                                    <tr>
                                                        <td>運費</td>
                                                        <td class="text-right"><span id="shipping_fee_table">0</span>
                                                        </td>
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
                                            <input hidden name="source_html" value="{{$source_html}}">

                                            {!! Form::hidden('redirect_to', old('redirect_to', URL::previous())) !!}
                                    </div>

                                    <!-- Button -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"></label>
                                        <div class="col-md-4">
                                            <a class="btn btn-danger"
                                               href="{{ old('redirect_to', URL::previous())}}">取消</a>
                                            <button type="submit" class="btn btn-primary">更新</button>
                                        </div>
                                    </div>

                        </div>
                    </div>

                </fieldset>

            </form>
            {{--    </div>--}}


        </section>
        <!-- /.content -->
    {{--    </div>--}}
    <!-- /.content-wrapper -->
@endsection
