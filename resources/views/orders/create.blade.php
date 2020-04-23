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
                <form class="well form-horizontal" action="" method="post" id="contact_form">
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
                                        <option value="">Select a customer...</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                    <script>
                                        var customer_select_id;
                                        $('#select_customer').selectize({
                                            onChange: function (value) {
                                                customer_select_id = value;
                                                console.log(customer_select_id);
                                                $.ajax({
                                                    url: '/ajax/get_customer_concat_persons',
                                                    data: {customer_select_id: customer_select_id}
                                                })
                                                    .done(function (res) {
                                                        console.log(res);
                                                        const myNode = document.getElementById("dynamic_concat_person");
                                                        myNode.innerHTML = '';
                                                        html = '<select name="business_concat_person_id" class="form-control">';
                                                        html += '<option value=-1>請選擇福委</option>'
                                                        for (let [key, value] of Object.entries(res)) {
                                                            html += '<option value=\"' + key + '\">' + value + '</option>'
                                                        }
                                                        html += '</select>'
                                                        $('#dynamic_concat_person').append(html);
                                                    })
                                            }
                                        });
                                    </script>
                                </div>

                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">公司名稱</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" name="other_name" placeholder="非客戶名單之補充"
                                           value="{{ old('other_name') }}">
                                </div>
                            </div>
                            <div class="col-md-2 inputGroupContainer">
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
                                        <select  class="form-control">
                                            <option disabled value=-1>請選擇福利目的</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3 inputGroupContainer">
                                <label class=" control-label">訂購窗口</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" name="other_concat_person_name"
                                           placeholder="非名單內補充"
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
                                <label class="control-label">負責業務</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <select id="user_id" name="user_id" class="form-control">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">狀態</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                    <select id="user_id" name="user_id" class="form-control">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">收貨日期</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="date" class="form-control" id="tax_id" name="tax_id"
                                           value="{{ old('tax_id') }}">
                                </div>
                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">最遲到貨日期</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i
                                            class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="date" class="form-control" id="tax_id" name="tax_id"
                                           value="{{ old('tax_id') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 inputGroupContainer">
                                <label class="control-label">地址</label>

                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <input type="text" class="form-control" id="capital" name="capital"
                                           placeholder="請輸入資本額" value="{{ old('capital') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 inputGroupContainer">
                                <label class=" control-label">統編</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="請輸入名稱"
                                           value="{{ old('name') }}">
                                </div>

                            </div>
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">付款方式</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="phone"
                                           value="{{ old('name') }}">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-md-4 inputGroupContainer">
                                <label class="control-label">備註</label>

                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                    <textarea class="form-control" id="note" name="note" placeholder="請輸入備註"
                                    >{{ old('note') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row text-left">
                            <label class="col-md-2 ">Product</label>
                            <label class="col-md-2 ">Detail</label>
                            <label class="col-md-2 ">Quantity</label>
                            <label class="col-md-2 ">Price</label>
                        </div>
                        <div id="product_list" class="row">
                            <input class="col-md-2">
                            <input class="col-md-2">
                            <input class="col-md-2">
                            <input class="col-md-2">
                        </div>
                        <br>


                    {!! Form::hidden('redirect_to', old('redirect_to', URL::previous())) !!}
                    <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <a class="btn btn-danger" href="{{ old('redirect_to', URL::previous())}}">取消</a>
                                {{--                                <a class="btn btn-danger" href="{{ URL::previous() }}">取消</a>--}}
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
