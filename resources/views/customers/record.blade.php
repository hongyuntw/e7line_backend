@extends('layouts.master')

@section('title', '客戶紀錄')

@section('content')
    <meta id="csrf_token" name="csrf_token" content="{{ csrf_token() }}"/>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                客戶紀錄
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('customers.index')}}"><i class="fa fa-shopping-bag"></i> 客戶資料</a></li>
                <li class="active">客戶紀錄</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="tabs">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <!-- /.box-header -->
                            <div class="box-body">
                                {{--                            <h3 class="text-left">客戶資訊</h3>--}}
                                <div id="Customer">
                                    <h4 class="text-center">
                                        <label style="font-size: medium">客戶資訊</label>
                                    </h4>
                                </div>


{{--                                tabs bar--}}
                                <div style="right: 3px  ;float:right; position:fixed; background-color: #e7eeff;opacity: 0.7" >
                                    <ul class="nav navbar-default"  style="background-color: transparent">
                                        <li class="nav-item">
                                            <a style="color: black;font-weight:bold;writing-mode: vertical-lr;z-index: 0"
                                               href="#Customer">客戶資訊</a></li>
                                        <li class="nav-item">
                                            <a style="color: black;font-weight:bold;writing-mode: vertical-lr"
                                               href="#ConcatWindow">聯繫窗口</a></li>
                                        <li class="nav-item">
                                            <a style="color: black;font-weight:bold;writing-mode: vertical-lr"
                                               href="#Development_Record">開發紀錄</a></li>
                                        <li class="nav-item">
                                            <a style="color: black;font-weight:bold;writing-mode: vertical-lr"
                                               href="#Welfare">福利資訊</a></li>
                                    </ul>
                                </div>
                                <br>
                                <br>
                                <br>

                                <table class="table table-striped">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 10px;">客戶名稱</th>
                                        <th class="text-center" style="width: 10px;">聯絡電話</th>
                                        <th class="text-center" style="width: 10px;">規模</th>
                                        <th class="text-center" style="width: 10px;">地區</th>
                                        <th class="text-center" style="width: 10px;">狀態</th>
                                        <th class="text-center" style="width: 10px;">是否開通</th>
                                        <th class="text-center" style="width: 10px;">其他功能</th>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td class="text-center">{{$customer->name}}</td>
                                        <td class="text-center">{{$customer->phone_number}}</td>
                                        <td class="text-center">{{$customer->scales}} 人</td>
                                        <td class="text-center">{{$customer->city}}{{$customer->area}}</td>
                                        <td class="text-center">
                                            <select id="customer_status" name="customer_status">
                                                @foreach([1,2,3,4,5] as $st_id)
                                                    <option
                                                        value="{{ $st_id }}"{{ (old('$st_id', $customer->status) == $st_id)? ' selected' : '' }}>{{ $status_text[$st_id]  }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <select id="active_status" name="active_status">
                                                <option
                                                    value="0" {{ old('active_status',$customer->active_status) == 0 ? 'selected' : '' }}>
                                                    否
                                                </option>
                                                <option
                                                    value="1" {{ old('active_status',$customer->active_status) == 1 ? 'selected' : '' }}>
                                                    是
                                                </option>

                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button class="label label-warning" onclick="update_customer_status()">
                                                更新狀態
                                            </button>
                                            <a class="label label-primary"
                                               href="{{route('customers.edit',$customer->id)}}">編輯基本資訊</a>
                                        </td>
                                        {{--                                        編輯客戶狀態等--}}
                                        <script>
                                            function update_customer_status() {
                                                var customer_status = document.getElementsByName('customer_status')[0].value;
                                                var active_status = document.getElementsByName('active_status')[0].value;
                                                var customer_id = '{{$customer->id}}';
                                                $.ajax({
                                                    method: 'POST',
                                                    url: '{{ route('customers.update_status')}}',
                                                    data: {
                                                        customer_status: customer_status,
                                                        active_status: active_status,
                                                        customer_id: customer_id

                                                    },
                                                    // dataType: 'json',
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                                    },
                                                    success: function (data) {
                                                        // alert(data.success);
                                                        location.reload()
                                                    },
                                                    error: function (request) {
                                                        var error = JSON.parse(request.responseText);
                                                        var msg = '';
                                                        for (var prop in error['errors']) {
                                                            msg += error['errors'][prop] + '\n';
                                                        }
                                                        alert(msg);
                                                    }
                                                })


                                            }

                                        </script>
                                    </tr>
                                </table>

                            </div>

{{--                            聯繫窗口--}}

                            <div class="box-body">
                                <div id="ConcatWindow"></div>
                                <h4 class="text-center">
                                    <label style="font-size: medium">聯繫窗口</label>
                                </h4>
                                <br>
                                <br>
                                <br>
                                <a id="add_concat_btn" class="btn btn-link" onclick="add_concat_person_dynamic_field()">
                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                    add concat window
                                </a>
                                <br>
                                <div id="add_concat_form"></div>


                                {{--                        新增聯絡人--}}
                                <script>
                                    function add_concat_person_dynamic_field() {
                                        html = '<form method="post" id="dynamic_form">';
                                        html += '@csrf';
                                        html += '<input type="text" name="name" placeholder="姓名" class="form-control"/>';
                                        html += '<input type="text" name="phone_number" placeholder="聯絡電話" class="form-control"/>';
                                        html += '<input type="text" name="extension_number" placeholder="分機" class="form-control"/>';
                                        html += '<input type="text" name="email" placeholder="email" class="form-control"/>';
                                        html += '<label>是否希望收到信？</label><br>'
                                        html += '<select class="form-control" name="want_receive_mail"><option value="1">是</option><option value="0">否</option></select>'
                                        html += '<button type="button" name="add" id="add" class="btn btn-primary" onclick="insert_concat_person()">Add</button>';
                                        html += '<button type="button" name="cancel_btn" id="cancel_btn" class="btn btn-danger" onclick="insert_concat_person_cancel_btn_click()">Cancel</button>';
                                        html += '</form>';
                                        $('#add_concat_form').append(html);
                                    }

                                    function insert_concat_person_cancel_btn_click() {
                                        const myNode = document.getElementById("add_concat_form");
                                        myNode.innerHTML = '';
                                    }

                                    function insert_concat_person() {
                                        var name = document.getElementsByName('name')[0].value;
                                        var phone_number = document.getElementsByName('phone_number')[0].value;
                                        var extension_number = document.getElementsByName('extension_number')[0].value;
                                        var email = document.getElementsByName('email')[0].value;
                                        var customer_id = '{{$customer->id}}';
                                        $.ajax({
                                            method: 'POST',
                                            url: '{{ route('customers.add_concat_person') }}',
                                            data: {
                                                name: name,
                                                phone_number: phone_number,
                                                extension_number: extension_number,
                                                email: email,
                                                customer_id: customer_id
                                            },
                                            // dataType: 'json',
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                            },
                                            success: function (data) {
                                                alert(data.success);
                                                location.reload()
                                            },
                                            error: function (request) {
                                                var error = JSON.parse(request.responseText);
                                                var msg = '';
                                                for (var prop in error['errors']) {
                                                    msg += error['errors'][prop] + '\n';
                                                }
                                                alert(msg);
                                            }
                                        })
                                    }

                                </script>

                                {{--                        edit聯絡人--}}
                                <script>
                                    {{--                            when edit btn be clicked--}}
                                    function edit_btn_reply_click(clicked_btn_name) {
                                        var edit_btn_name = clicked_btn_name;
                                        // console.log(edit_btn_name);
                                        show_edit_concat(edit_btn_name);
                                    };

                                    //when edit_confirm be clicked
                                    function edit_confirm_btn_reply_click(clicked_btn_name) {
                                        var edit_btn_name = clicked_btn_name;
                                        // console.log(edit_btn_name);
                                        update_edit_concat(edit_btn_name);
                                    };

                                    // update data to db
                                    function update_edit_concat(edit_btn_name) {
                                        var edit_inputs = document.getElementsByName(edit_btn_name);
                                        var concat_person_id = edit_btn_name.substring(12);
                                        var input_values = [];
                                        for (var i = 0; i < 6; i++) {
                                            input_values.push(edit_inputs[i].value);
                                        }
                                        var name = input_values[0];
                                        var phone_number = input_values[1];
                                        var extension_number = input_values[2];
                                        var email = input_values[3];
                                        var want_receive_mail = input_values[4]
                                        var is_left = input_values[5];
                                        var customer_id = '{{$customer->id}}';
                                        $.ajax({
                                            method: 'POST',
                                            url: '{{ route('customers.update_concat_person') }}',
                                            data: {
                                                want_receive_mail: want_receive_mail,
                                                name: name,
                                                phone_number: phone_number,
                                                extension_number: extension_number,
                                                email: email,
                                                is_left: is_left,
                                                customer_id: customer_id,
                                                concat_person_id: concat_person_id,
                                            },
                                            // dataType: 'json',
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                            },
                                            success: function (data) {
                                                alert(data.success);
                                                location.reload()
                                            },
                                            error: function (request) {
                                                var error = JSON.parse(request.responseText);
                                                var msg = '';
                                                for (var prop in error['errors']) {
                                                    msg += error['errors'][prop] + '\n';
                                                }
                                                alert(msg);
                                            }
                                        })
                                    };

                                    // cancel be clicked
                                    function edit_cancel_btn_reply_click(clicked_btn_name) {
                                        var edit_btn_name = clicked_btn_name;
                                        // console.log(edit_btn_name);
                                        hide_edit_concat(edit_btn_name);
                                    };

                                    //hide btn
                                    function hide_edit_concat(edit_btn_name) {
                                        var edit_inputs = document.getElementsByName(edit_btn_name);
                                        // console.log(edit_inputs)
                                        for (var i = 0; i < edit_inputs.length - 1; i++) {
                                            // edit_inputs[i].setAttribute("class", "democlass");
                                            // if(i>=edit_inputs.length-3){
                                            //     edit_inputs[i].style.visibility= 'hidden'
                                            // }
                                            // else{
                                            edit_inputs[i].style.display = 'none';

                                            // }

                                        }
                                        // console.log(edit_inputs)
                                    };

                                    //show btn
                                    function show_edit_concat(edit_btn_name) {
                                        var edit_inputs = document.getElementsByName(edit_btn_name);
                                        // console.log(edit_inputs)

                                        for (var i = 0; i < edit_inputs.length; i++) {
                                            // edit_inputs[i].setAttribute("class", "democlass");
                                            // if(i>=edit_inputs.length-3){
                                            //     edit_inputs[i].style.visibility= 'visible'
                                            // }
                                            // else{
                                            edit_inputs[i].style.display = '';
                                            // }
                                        }
                                        // console.log(edit_inputs)
                                    };
                                </script>


                                <table class="table table-striped">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center col-1">聯絡人姓名</th>
                                        <th class="text-center col-1">聯絡電話</th>
                                        <th class="text-center col-1">分機</th>
                                        <th class="text-center col-3">聯絡信箱</th>
                                        <th class="text-center col-3">收信意願</th>
                                        <th class="text-center col-1">是否離職</th>
                                        <th class="text-center col-1">創建日期</th>

                                        <th class="text-center col-1">其他功能</th>

                                    </tr>
                                    </thead>
                                    @foreach ($business_concat_persons as $concat_person)
                                        <tr class="text-center">
                                            <td class="text-left">
                                                <svg class="bi bi-person-fill" width="1em" height="1em"
                                                     viewBox="0 0 16 16"
                                                     fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                     @if($concat_person->is_left==0) style="color:green" @endif>
                                                    <path fill-rule="evenodd"
                                                          d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                                {{$concat_person->name}}
                                                <input type="text" name="edit_concat_{{$concat_person->id}}"
                                                       class="form-control"
                                                       style="display: none" value="{{ $concat_person->name }}">
                                            </td>
                                            <td class="text-left">
                                                {{$concat_person->phone_number}}
                                                <input type="text" name="edit_concat_{{$concat_person->id}}"
                                                       class="form-control"
                                                       style="display: none" value="{{ $concat_person->phone_number }}">
                                            </td>
                                            <td class="text-left">
                                                {{$concat_person->extension_number}}

                                                <input type="text" name="edit_concat_{{$concat_person->id}}"
                                                       class="form-control"
                                                       style="display: none"
                                                       value="{{ $concat_person->extension_number }}">
                                            </td>
                                            <td class="text-left">
                                                {{$concat_person->email}}
                                                <input type="text" name="edit_concat_{{$concat_person->id}}"
                                                       class="form-control"
                                                       style="display: none" value="{{ $concat_person->email }}">
                                            </td>
                                            <td class="text-center">
                                                @if($concat_person->want_receive_mail)<span
                                                    class="glyphicon glyphicon-ok"></span>@else<span
                                                    class="glyphicon glyphicon-remove"></span>@endif
                                                <select style="display: none" name="edit_concat_{{$concat_person->id}}"
                                                        class="form-control">
                                                    {{--                                            <option value="0 @if($customer->is_left==0) 'selected' @endif" >否</option>--}}
                                                    {{--                                            <option value="1 @if($customer->is_left==1) 'selected' @endif" >是</option>--}}
                                                    <option value="0"
                                                            @if($concat_person->want_receive_mail==0) selected="selected" @endif>
                                                        否
                                                    </option>
                                                    <option value="1"
                                                            @if($concat_person->want_receive_mail==1) selected="selected" @endif>
                                                        是
                                                    </option>
                                                </select>


                                            </td>
                                            <td class="text-center">
                                                @if($concat_person->is_left==0)<span
                                                    class="glyphicon glyphicon-remove"></span>@else<span
                                                    class="glyphicon glyphicon-ok"></span>@endif
                                                <select style="display: none" name="edit_concat_{{$concat_person->id}}"
                                                        class="form-control">
                                                    {{--                                            <option value="0 @if($customer->is_left==0) 'selected' @endif" >否</option>--}}
                                                    {{--                                            <option value="1 @if($customer->is_left==1) 'selected' @endif" >是</option>--}}
                                                    <option value="0"
                                                            @if($concat_person->is_left==0) selected="selected" @endif>否
                                                    </option>
                                                    <option value="1"
                                                            @if($concat_person->is_left==1) selected="selected" @endif>是
                                                    </option>
                                                </select>

                                            </td>
                                            <td class="text-center">{{$concat_person->create_date}}</td>
                                            <td class="text-center">
                                                <button onClick="edit_confirm_btn_reply_click(this.name)"
                                                        class="label label-success text-center"
                                                        name="edit_concat_{{$concat_person->id}}"
                                                        style="display: none;">
                                                    確認
                                                </button>
                                                <button onClick="edit_cancel_btn_reply_click(this.name)"
                                                        class="label label-danger text-center"
                                                        name="edit_concat_{{$concat_person->id}}"
                                                        style="display: none;">
                                                    取消
                                                </button>
                                                <button onClick="edit_btn_reply_click(this.name)"
                                                        class="label label-info text-center"
                                                        name="edit_concat_{{$concat_person->id}}">
                                                    編輯
                                                </button>
                                            </td>

                                        </tr>
                                    @endforeach
                                </table>


                            </div>



{{--                            開發紀錄--}}
                            <div class="box-body">
                                <div id="Development_Record">
                                    <h4 class="text-center">
                                        <label style="font-size: medium">開發紀錄</label>
                                    </h4>
                                </div>

                                <br>
                                <br>
                                <br>
                                <a id="add_record_btn" name="add_record_btn" class="btn btn-link"
                                   onclick="add_record_dynamic_field()">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                    New Record
                                </a>

                                <span id="add_record_form"></span>

                                {{--新增concat record--}}
                                <script>
                                    function add_record_dynamic_field() {
                                        html = '<form method="post" id="dynamic_form">';
                                        {{--html += '@csrf';--}}
                                            html += '<label>狀態</label> <br>';
                                        html += '<select name="status">';
                                        html += '<option value=0>已完成</option>';
                                        html += '<option value=1>待追蹤</option>'
                                        html += '<option value=2>其他</option>'
                                        html += '</select>';


                                        html += '<input type="text" name="development_content" placeholder="開發內容" class="form-control"/>';
                                        html += '<input type="text" name="track_content" placeholder="追蹤內容" class="form-control"/>';
                                        html += '<label>追蹤日期</label> <br>';

                                        html += '<input type="date" name="track_date" /> <br>';


                                        html += '<button onclick="insert_record()" type="button" name="add_record" id="add_record" class="btn btn-primary">Add</button>';
                                        html += '<button onclick="add_record_cancel_btn_click()" type="button" name="record_cancel_btn" id="record_cancel_btn" class="btn btn-danger">Cancel</button>';
                                        html += '</form>';
                                        $('#add_record_form').append(html);
                                    }

                                    function insert_record() {
                                        var status = document.getElementsByName('status')[0].value;
                                        var development_content = document.getElementsByName('development_content')[0].value;
                                        var track_content = document.getElementsByName('track_content')[0].value;
                                        var track_date = document.getElementsByName('track_date')[0].value;
                                        var customer_id = '{{$customer->id}}';
                                        $.ajax({
                                            method: 'POST',
                                            url: '{{ route('customers.add_concat_record') }}',
                                            data: {
                                                track_content: track_content,
                                                status: status,
                                                development_content: development_content,
                                                track_date: track_date,
                                                customer_id: customer_id
                                            },
                                            // dataType: 'json',
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                            },
                                            success: function (data) {
                                                // alert(data.success);
                                                location.reload()
                                            },
                                            error: function (request) {
                                                var error = JSON.parse(request.responseText);
                                                var msg = '';
                                                for (var prop in error['errors']) {
                                                    msg += error['errors'][prop] + '\n';
                                                }
                                                alert(msg);
                                            }
                                        })
                                    }

                                    function add_record_cancel_btn_click() {
                                        const myNode = document.getElementById("add_record_form");
                                        myNode.innerHTML = '';
                                    }

                                </script>


                                <table class="table table-striped" width="100%">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 8%;">status</th>
                                        <th class="text-center" style="width: 25%;">開發note</th>
                                        <th class="text-center" style="width: 25%;">追蹤note</th>
                                        <th class="text-center" style="width: 10%;">待追蹤日期</th>
                                        <th class="text-center" style="width: 10%;">創建日期</th>
                                        <th class="text-center" style="width: 15%;">其他功能</th>

                                        {{--                                <th class="text-center" style="width: 10px;">功能</th>--}}


                                    </tr>
                                    </thead>
                                    @foreach ($concat_records as $concat_record)
                                        @php
                                            $status_name = '';
                                            $status_css = '';
                                        @endphp
                                        @if($concat_record->status==0)
                                            @php($status_name = '已完成')
                                            @php($status_css = 'label label-success')
                                        @elseif($concat_record->status==1)
                                            @php($status_name = '待追蹤')
                                            @php($status_css = 'label label-warning')
                                        @elseif($concat_record->status==2)
                                            @php($status_name = '其他')
                                            @php($status_css = 'label label-primary')
                                        @endif


                                        <tr class="text-center">
                                            <td class="align-middle" style="vertical-align: middle">
                                                <label style="min-width: 60px;display: inline-block;" class="{{$status_css}}">{{$status_name}}</label>
                                                {{--                                                 hidden--}}
                                                <select style="display:none;height: 25px" class="form-control"
                                                        name="edit_concat_record_info{{$concat_record->id}}">
                                                    <option value="0" @if($concat_record->status==0)selected @endif>
                                                        已完成
                                                    </option>
                                                    <option value="1" @if($concat_record->status==1)selected @endif>
                                                        待追蹤
                                                    </option>
                                                    <option value="2" @if($concat_record->status==2)selected @endif>其他
                                                    </option>
                                                </select>


                                            </td>
                                            <style>
                                                textarea:hover{
                                                    height: 6em;
                                                }
                                            </style>
                                            <td class="align-middle" style="vertical-align: middle">
                                                <textarea disabled="true"
                                                          name="edit_concat_record_info{{$concat_record->id}}"
                                                          class="form-control" rows="2"
                                                          value="{{$concat_record->development_content}}"
                                                          style="text-align: left;vertical-align: top;">{{$concat_record->development_content}}</textarea>
                                            </td>
                                            <td class="align-middle" style="vertical-align: middle">
                                                <textarea disabled="true" onfocus="this.rows=4;"
                                                          name="edit_concat_record_info{{$concat_record->id}}"
                                                          class="form-control" rows="2"
                                                          value="{{$concat_record->track_content}}">{{$concat_record->track_content}}</textarea>
                                            </td>
                                            <td class="align-middle" style="vertical-align: middle">
                                                {{date("Y-m-d", strtotime($concat_record->track_date))}}
                                                {{--                                                hidden--}}
                                                <input value="{{date("Y-m-d", strtotime($concat_record->track_date))}}"
                                                       type="date" style="display: none" class="form-control text-center"
                                                       name="edit_concat_record_info{{$concat_record->id}}">
                                            </td>
                                            <td class="align-middle" style="vertical-align: middle">{{date("Y-m-d", strtotime($concat_record->create_date))}}</td>
                                            <td class="align-middle" style="vertical-align: middle">
                                                <button onClick="confirm_concat_record_btn_reply_click(this.name)"
                                                        class="label label-success" style="display: none"
                                                        name="edit_concat_record_info{{$concat_record->id}}">
                                                    確認
                                                </button>
                                                <button onClick="cancel_concat_record_btn_reply_click(this.name)"
                                                        class="label label-danger" style="display: none"
                                                        name="edit_concat_record_info{{$concat_record->id}}">
                                                    取消
                                                </button>
                                                <button onClick="edit_concat_record_btn_reply_click(this.name)"
                                                        class="label label-info"
                                                        name="edit_concat_record_info{{$concat_record->id}}">
                                                    編輯
                                                </button>
                                            </td>
                                            {{--                        edit concat record--}}
                                            <script>
                                                {{--                            when edit btn be clicked--}}
                                                function edit_concat_record_btn_reply_click(clicked_btn_name) {
                                                    var btn_name = clicked_btn_name;
                                                    // console.log(edit_btn_name);
                                                    show_edit_concat_record(btn_name);
                                                };

                                                //when edit_confirm be clicked
                                                function confirm_concat_record_btn_reply_click(clicked_btn_name) {
                                                    update_edit_concat_record(clicked_btn_name);
                                                };

                                                // update data to db
                                                function update_edit_concat_record(btn_name) {
                                                    var inputs = document.getElementsByName(btn_name);
                                                    var input_values = [];
                                                    for (var i = 0; i < 4; i++) {
                                                        input_values.push(inputs[i].value);
                                                    }
                                                    // console.log(input_values[1]);
                                                    var record_status = input_values[0];
                                                    var development_content = input_values[1];
                                                    var track_content = input_values[2];
                                                    var track_date = input_values[3];
                                                    var concat_record_id = btn_name.substring(23);
                                                    var customer_id = '{{$customer->id}}';
                                                    $.ajax({
                                                        method: 'POST',
                                                        url: '{{ route('customers.update_concat_record') }}',
                                                        data: {
                                                            record_status: record_status,
                                                            development_content: development_content,
                                                            track_content: track_content,
                                                            track_date: track_date,
                                                            concat_record_id: concat_record_id,
                                                            customer_id: customer_id,
                                                        },
                                                        // dataType: 'json',
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                                        },
                                                        success: function (data) {
                                                            // alert(data.success);
                                                            location.reload()
                                                        },
                                                        error: function (request) {
                                                            var error = JSON.parse(request.responseText);
                                                            var msg = '';
                                                            for (var prop in error['errors']) {
                                                                msg += error['errors'][prop] + '\n';
                                                            }
                                                            alert(msg);
                                                        }
                                                    })
                                                };

                                                // cancel be clicked
                                                function cancel_concat_record_btn_reply_click(clicked_btn_name) {
                                                    hide_edit_concat_record(clicked_btn_name);
                                                };

                                                //hide btn
                                                function hide_edit_concat_record(btn_name) {
                                                    var inputs = document.getElementsByName(btn_name);
                                                    for (var i = 0; i < inputs.length - 1; i++) {
                                                        if (i == 1 || i == 2) {
                                                            inputs[i].disabled = true;
                                                        } else {
                                                            inputs[i].style.display = 'none';

                                                        }
                                                    }
                                                };

                                                //show btn
                                                function show_edit_concat_record(btn_name) {
                                                    var inputs = document.getElementsByName(btn_name);
                                                    for (var i = 0; i < inputs.length; i++) {
                                                        if (i == 1 || i == 2) {
                                                            inputs[i].disabled = false;
                                                        } else {
                                                            inputs[i].style.display = '';

                                                        }
                                                    }
                                                };
                                            </script>
                                        </tr>
                                    @endforeach

                                </table>
                                <tfoot>
                                {{$concat_records->links()}}
                                </tfoot>
                            </div>

{{--                            福利資訊--}}
                            <div class="box-body">
                                <div id="Welfare">
                                    <h4 class="text-center">
                                        <label style="font-size: medium">福利資訊</label>
                                    </h4>
                                </div>


                                <br>
                                <br>
                                <br>
                                <a id="add_welfare_btn" name="add_welfare_btn" class="btn btn-link">
                                    <i class="glyphicon glyphicon-pencil"></i> Update Detail
                                </a><br>


                                {{--                        add 福利資訊--}}
                                <script>
                                    {{--                            when edit btn be clicked--}}
                                    function add_welfare_btn_reply_click(clicked_btn_name) {
                                        var name = clicked_btn_name;
                                        show_add_welfare(name);
                                    };

                                    //show btn
                                    function show_add_welfare(name) {
                                        var add_inputs = document.getElementsByName(name);
                                        console.log('all_welfare_type' + name.substring(12))
                                        var welfare_types_delete_btn = document.getElementsByName('all_welfare_type_' + name.substring(12))
                                        for (var i = 0; i < welfare_types_delete_btn.length; ++i) {
                                            welfare_types_delete_btn[i].style.display = '';
                                        }
                                        for (var i = 0; i < add_inputs.length; i++) {
                                            // edit_inputs[i].setAttribute("class", "democlass");
                                            if (i == 1 || i == 2) {
                                                add_inputs[i].disabled = false;
                                            } else {
                                                add_inputs[i].style.display = '';

                                            }
                                        }
                                    };

                                    // cancel be clicked
                                    function cancel_welfare_btn_reply_click(clicked_btn_name) {
                                        var name = clicked_btn_name;
                                        // console.log(edit_btn_name);
                                        hide_add_welfare(name);
                                    };

                                    //hide btn
                                    function hide_add_welfare(name) {
                                        var add_inputs = document.getElementsByName(name);
                                        var welfare_types_delete_btn = document.getElementsByName('all_welfare_type_' + name.substring(12))
                                        for (var i = 0; i < welfare_types_delete_btn.length; ++i) {
                                            welfare_types_delete_btn[i].style.display = 'none';
                                        }
                                        // console.log(edit_inputs)
                                        for (var i = 0; i < add_inputs.length - 1; i++) {
                                            // edit_inputs[i].setAttribute("class", "democlass");
                                            if (i == 1 || i == 2) {
                                                add_inputs[i].disabled = true;
                                            } else {
                                                add_inputs[i].style.display = 'none';

                                            }
                                        }
                                        // console.log(edit_inputs)
                                    };

                                    //when edit_confirm be clicked
                                    function confirm_welfare_btn_reply_click(clicked_btn_name) {
                                        var name = clicked_btn_name;
                                        // console.log(edit_btn_name);
                                        update_add_welfare(name);
                                    };

                                    // update data to db
                                    function update_add_welfare(name) {
                                        var add_inputs = document.getElementsByName(name);
                                        // console.log(add_inputs);
                                        // console.log(welfare_status_id);
                                        var input_values = [];
                                        for (var i = 0; i < 3; i++) {
                                            input_values.push(add_inputs[i].value);
                                        }
                                        // console.log(welfare_id);
                                        var welfare_status_id = name.substring(12);
                                        var welfare_code = input_values[0];
                                        var note = input_values[1];
                                        var budget = input_values[2]
                                        var customer_id = '{{$customer->id}}';
                                        $.ajax({
                                            method: 'POST',
                                            url: '{{ route('customers.add_welfare_types') }}',
                                            data: {
                                                budget: budget,
                                                welfare_status_id: welfare_status_id,
                                                welfare_code: welfare_code,
                                                note: note,
                                                customer_id: customer_id,
                                            },
                                            // dataType: 'json',
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                            },
                                            success: function (data) {
                                                // alert(data.success);
                                                location.reload()
                                            },
                                            error: function (request) {
                                                var error = JSON.parse(request.responseText);
                                                var msg = '';
                                                for (var prop in error['errors']) {
                                                    msg += error['errors'][prop] + '\n';
                                                }
                                                alert(msg);
                                            }
                                        })
                                    };


                                </script>


                                <table class="table table-striped">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 10px;">目的</th>
                                        <th class="text-center" style="width: 10px;">福利類別</th>
                                        <th class="text-center" style="width: 10px;">Note</th>
                                        <th class="text-center" style="width: 10px;">Budget</th>
                                        <th class="text-center" style="width: 10px;">其他功能</th>


                                    </tr>

                                    {{--                                                刪除某個福利--}}
                                    <script>
                                        function delete_welfare_type_btn_click(type_id) {
                                            // console.log(type_id);
                                            $.ajax({
                                                method: 'POST',
                                                url: '{{ route('customers.delete_welfare_type') }}',
                                                data: {
                                                    type_id: type_id,
                                                },
                                                // dataType: 'json',
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                                },
                                                success: function (data) {
                                                    // alert(data.success);
                                                    location.reload()
                                                },
                                                error: function (request) {
                                                    var error = JSON.parse(request.responseText);
                                                    var msg = '';
                                                    for (var prop in error['errors']) {
                                                        msg += error['errors'][prop] + '\n';
                                                    }
                                                    alert(msg);
                                                }
                                            })

                                        }
                                    </script>

                                    </thead>
                                    @foreach ($welfarestatus as $welfare_status)
                                        <tr class="text-center">
                                            <td style="vertical-align: middle">{{ $welfare_status->welfare_name}}</td>
                                            <td class="text-left" style="vertical-align: middle">

                                                @foreach($welfare_status->welfare_types as $wtype)

                                                        <li>{{$wtype->welfare_type_name->name}}<a id="{{$wtype->id}}"
                                                                               name="all_welfare_type_{{$welfare_status->id}}"
                                                                                style="color:darkred;display: none;text-shadow:0 1px 0 #fff;
                                                                                font-size: 21px;font-weight: 700;line-height: 1;opacity: 2;cursor: pointer"
                                                                               onclick="delete_welfare_type_btn_click(this.id)">x</a></li>



                                                @endforeach
                                                <select style="display: none" name="add_welfare_{{$welfare_status->id}}"
                                                        class="custom-select">
                                                    <option value="-1">無</option>
                                                    @foreach($welfare_type_names as $welfare_type_name)
                                                        <option
                                                            value="{{$welfare_type_name->id}}">{{$welfare_type_name->name}}</option>
                                                    @endforeach
                                                </select>

                                            </td>
                                            <td class="align-middle" style="vertical-align: middle">
                                                <textarea disabled="true" name="add_welfare_{{$welfare_status->id}}"
                                                          class="form-control" rows="2"
                                                          id="comment">{{$welfare_status->note}}</textarea>
                                                <br>
                                            </td>
                                            <td class="align-middle" style="vertical-align: middle">
                                                <input  disabled="true" style="text-align: center;top: 50%"  name="add_welfare_{{$welfare_status->id}}"
                                                       value="{{$welfare_status->budget}}">

                                            </td>
                                            <td style="vertical-align: middle">
                                                <button onClick="confirm_welfare_btn_reply_click(this.name)"
                                                        class="label label-success"
                                                        name="add_welfare_{{$welfare_status->id}}"
                                                        style="display:none">
                                                    確認
                                                </button>
                                                <button onClick="cancel_welfare_btn_reply_click(this.name)"
                                                        class="label label-danger"
                                                        name="add_welfare_{{$welfare_status->id}}"
                                                        style="display:none">
                                                    取消
                                                </button>
                                                <button onClick="add_welfare_btn_reply_click(this.name)"
                                                        class="label label-info"
                                                        name="add_welfare_{{$welfare_status->id}}">
                                                    編輯
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>


                            <!-- /.box-body -->
                            <div class="box-footer clearfix">
                                {{--                            {{ $members->links()}}--}}
                            </div>
                        </div>
                        <!-- /.box -->
                    </div>
                </div>

                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
