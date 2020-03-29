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

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            {{--                            <h3 class="text-left">客戶資訊</h3>--}}
                            <h4 class="text-center">
                                <label style="font-size: medium">客戶資訊</label>
                            </h4>
                        </div>
                        <table class="table table-striped">
                            <thead style="background-color: lightgray">
                            <tr class="text-center">
                                <th class="text-center" style="width: 10px;">客戶名稱</th>
                                <th class="text-center" style="width: 10px;">聯絡電話</th>
                            </tr>
                            </thead>
                            <tr>
                                <td class="text-center">{{$customer->name}}</td>
                                <td class="text-center">{{$customer->phone_number}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-body">
                        <h4 class="text-center">
                            <label style="font-size: medium">Concat Window</label>
                        </h4>
                        <a id="add_concat_btn" class="btn btn-link">
                            <i class="glyphicon glyphicon-plus-sign"></i>
                            add concat window
                        </a>
                        <div id="add_concat_form"></div>


{{--                        新增聯絡人--}}
                        <script>
                            $(document).ready(function () {
                                function dynamic_field() {
                                    html = '<form method="post" id="dynamic_form">';
                                    html += '@csrf';
                                    html += '<input type="text" name="name" placeholder="姓名" class="form-control"/>';
                                    html += '<input type="text" name="phone_number" placeholder="聯絡電話" class="form-control"/>';
                                    html += '<input type="text" name="extension_number" placeholder="分機" class="form-control"/>';
                                    html += '<input type="text" name="email" placeholder="email" class="form-control"/>';
                                    html += '<button type="button" name="add" id="add" class="btn btn-primary">Add</button>';
                                    html += '<button type="button" name="cancel_btn" id="cancel_btn" class="btn btn-danger">Cancel</button>';
                                    html += '</form>';
                                    $('#add_concat_form').append(html);
                                }

                                $(document).on('click', '#add_concat_btn', function () {
                                    dynamic_field();
                                });
                                $(document).on('click', '#cancel_btn', function () {
                                    const myNode = document.getElementById("add_concat_form");
                                    myNode.innerHTML = '';
                                });
                                $(document).on('click', '#add', function () {
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
                                });
                            });
                        </script>

                        {{--                        edit聯絡人--}}
                        <script>
{{--                            when edit btn be clicked--}}
                            function edit_btn_reply_click(clicked_btn_name){
                                var edit_btn_name  = clicked_btn_name;
                                // console.log(edit_btn_name);
                                show_edit_concat(edit_btn_name);
                            };
                            //when edit_confirm be clicked
                            function edit_confirm_btn_reply_click(clicked_btn_name){
                                var edit_btn_name  = clicked_btn_name;
                                // console.log(edit_btn_name);
                                update_edit_concat(edit_btn_name);
                            };
                            // update data to db
                            function update_edit_concat(edit_btn_name){
                                var edit_inputs = document.getElementsByName(edit_btn_name);
                                var concat_person_id = edit_btn_name.substring(12);
                                var input_values = [];
                                for (var i = 0; i < 5; i++) {
                                    input_values.push(edit_inputs[i].value);
                                }
                                var name = input_values[0];
                                var phone_number = input_values[1];
                                var extension_number = input_values[2];
                                var email = input_values[3];
                                var is_left = input_values[4];
                                var customer_id = '{{$customer->id}}';
                                $.ajax({
                                    method: 'POST',
                                    url: '{{ route('customers.update_concat_person') }}',
                                    data: {
                                        name: name,
                                        phone_number: phone_number,
                                        extension_number: extension_number,
                                        email: email,
                                        is_left: is_left,
                                        customer_id: customer_id,
                                        concat_person_id :concat_person_id,
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
                            function edit_cancel_btn_reply_click(clicked_btn_name){
                                var edit_btn_name  = clicked_btn_name;
                                // console.log(edit_btn_name);
                                hide_edit_concat(edit_btn_name);
                            };
                            //hide btn
                            function hide_edit_concat(edit_btn_name) {
                                var edit_inputs = document.getElementsByName(edit_btn_name);
                                // console.log(edit_inputs)
                                for (var i = 0; i < edit_inputs.length -1; i++) {
                                    // edit_inputs[i].setAttribute("class", "democlass");
                                    edit_inputs[i].style.display = 'none';
                                }
                                // console.log(edit_inputs)
                            };
                            //show btn
                            function show_edit_concat(edit_btn_name) {
                                var edit_inputs = document.getElementsByName(edit_btn_name);
                                // console.log(edit_inputs)

                                for (var i = 0; i < edit_inputs.length; i++) {
                                    // edit_inputs[i].setAttribute("class", "democlass");
                                    edit_inputs[i].style.display = 'block';
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
                                <th class="text-center col-1">是否離職</th>
                                <th class="text-center col-1"></th>

                            </tr>
                            </thead>
                            @foreach ($business_concat_persons as $concat_person)
                                <tr class="text-center">
                                    <td>
                                        <svg class="bi bi-person-fill" width="1em" height="1em" viewBox="0 0 16 16"
                                             fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                             @if($concat_person->is_left==0) style="color:green" @endif>
                                            <path fill-rule="evenodd"
                                                  d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        {{$concat_person->name}}
                                        <input type="text" name="edit_concat_{{$concat_person->id}}"
                                               style="display: none" value="{{ $concat_person->name }}">
                                    </td>
                                    <td>
                                        {{$concat_person->phone_number}}
                                        <input type="text" name="edit_concat_{{$concat_person->id}}"
                                               style="display: none"  value="{{ $concat_person->phone_number }}">
                                    </td>
                                    <td>
                                        {{$concat_person->extension_number}}

                                        <input type="text" name="edit_concat_{{$concat_person->id}}"
                                               style="display: none"  value="{{ $concat_person->extension_number }}">
                                    </td>
                                    <td>
                                        {{$concat_person->email}}
                                        <input type="text" name="edit_concat_{{$concat_person->id}}"
                                               style="display: none"  value="{{ $concat_person->email }}">
                                    </td>
                                    <td>
                                        @if($concat_person->is_left==0)否@else是@endif
                                        <select style="display: none" name="edit_concat_{{$concat_person->id}}">
{{--                                            <option value="0 @if($customer->is_left==0) 'selected' @endif" >否</option>--}}
{{--                                            <option value="1 @if($customer->is_left==1) 'selected' @endif" >是</option>--}}
                                            <option value="0" @if($concat_person->is_left==0) selected="selected" @endif>否</option>
                                            <option value="1" @if($concat_person->is_left==1) selected="selected" @endif>是</option>
                                        </select>

                                    </td>
                                    <td>
                                        <button onClick="edit_confirm_btn_reply_click(this.name)" class="label label-success" name="edit_concat_{{$concat_person->id}}" style="display:none">
                                            confirm
                                        </button>
                                        <button onClick="edit_cancel_btn_reply_click(this.name)" class="label label-danger" name="edit_concat_{{$concat_person->id}}" style="display:none">
                                            cancel
                                        </button>
                                        <button onClick="edit_btn_reply_click(this.name)" class="label label-info" name="edit_concat_{{$concat_person->id}}">
                                            edit
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        </table>





                    </div>
                    <div class="box-body">
                        <h4 class="text-center">
                            <label style="font-size: medium">Welfare</label>
                        </h4>
                        <a id="add_welfare_btn" name="add_welfare_btn" class="btn btn-link">
                            <i class="glyphicon glyphicon-pencil"></i> Update Detail
                        </a>
                        <div id="add_welfare_form"></div>



                        <table class="table table-striped">
                            <thead style="background-color: lightgray">
                            <tr class="text-center">
                                <th class="text-center" style="width: 10px;">目的</th>
                                <th class="text-center" style="width: 10px;">福利類別</th>
                                <th class="text-center" style="width: 10px;">提供之公司</th>
                                <th class="text-center" style="width: 10px;">Detail</th>
                            </tr>
                            </thead>
                            @foreach ($welfarestatus as $welfare_status)

                                <tr class="text-center">
                                    <td>{{ $welfare_status->welfare->name}}</td>
                                    <td>{{$welfare_status->welfare_type->name}}</td>
                                    <td>
                                        {{($welfare_status->welfare_company->name)}}
                                    </td>
                                    <td>
                                        {{($welfare_status->welfare_detail->name)}}

                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="box-body">
                        <h4 class="text-center">
                            <label style="font-size: medium">Development Record</label>
                        </h4>

                        <a id="add_record_btn" name="add_record_btn" class="btn btn-link">
                            <i class="glyphicon glyphicon-pencil"></i>
                            New Record
                        </a>
                        <div id="add_record_form"></div>

{{--新增concat record--}}
                        <script>
                            $(document).ready(function () {
                                function dynamic_field() {
                                    html = '<form method="post" id="dynamic_form">';
                                    html += '@csrf';
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


                                    html += '<button type="button" name="add_record" id="add_record" class="btn btn-primary">Add</button>';
                                    html += '<button type="button" name="record_cancel_btn" id="record_cancel_btn" class="btn btn-danger">Cancel</button>';
                                    html += '</form>';

                                    $('#add_record_form').append(html);


                                }

                                $(document).on('click', '#add_record_btn', function () {
                                    dynamic_field();
                                });
                                $(document).on('click', '#record_cancel_btn', function () {
                                    const myNode = document.getElementById("add_record_form");
                                    myNode.innerHTML = '';
                                });

                                $(document).on('click', '#add_record', function () {
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
                                });
                            });
                        </script>


                        <table class="table table-striped">
                            <thead style="background-color: lightgray">
                            <tr class="text-center">
                                <th class="text-center" style="width: 10px;">status</th>
                                <th class="text-center" style="width: 10px;">開發note</th>
                                <th class="text-center" style="width: 10px;">追蹤note</th>
                                <th class="text-center" style="width: 10px;">待追蹤日期</th>
                                <th class="text-center" style="width: 10px;">創建日期</th>
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
                                    <td><label class="{{$status_css}}">{{$status_name}}</label></td>
                                    <td>{{ $concat_record->development_content}}</td>
                                    <td>{{$concat_record->track_content}}</td>
                                    <td>{{$concat_record->track_date}}</td>
                                    <td>{{ $concat_record->create_date}}</td>
                                </tr>
                            @endforeach

                        </table>
                        <tfoot>
                        {{$concat_records->links()}}
                        </tfoot>
                    </div>

                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        {{--                            {{ $members->links()}}--}}
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
    </div>
    <!-- /.row -->

    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
