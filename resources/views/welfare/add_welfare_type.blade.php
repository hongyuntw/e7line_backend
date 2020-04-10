@extends('layouts.master')

@section('title', '新增福利類別')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                新增福利類別
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 福利狀況</a></li>
                <li class="active">新增福利類別</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">

                <form class="well form-horizontal" action="{{ route('welfare_status.store_welfare_type') }}" method="post" id="contact_form">
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
                        <!-- radio checks -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">請勾選想刪除之福利類別<br>(僅限管理員使用)</label>
                            <div class="col-md-4">
                                @foreach($welfare_type_names as $welfare_type_name)
                                    <div class="checkbox">
                                        <label>
                                            <input @if(Auth::user()->level!=2 || $welfare_type_name->is_deleted) disabled @endif type="checkbox" name="to_be_delete[]" value="{{$welfare_type_name->id}}"/>

                                            <input @if(Auth::user()->level!=2 || $welfare_type_name->is_deleted) disabled @endif ondblclick="edit_field_control(this.name)"  type="text" name="to_be_update[{{$welfare_type_name->id}}]" size="10" value="{{$welfare_type_name->name}}">
                                        </label>
                                        <br>
{{--                                        <input type="text" value="{{$welfare_type_name->name}}" name="to_be_update[]" placeholder="請新增福利名稱">--}}
                                    </div>
                                @endforeach
                                <script>
                                    function edit_field_control(name){
                                        var input = document.getElementsByName(name)[0];
                                        console.log(input.disabled);
                                        console.log(name);
                                    }

                                </script>
                            </div>
                        </div>
                        <div id="dynamic_add_field">
                        </div>

                        <script>
                            function add_input_field(){
                                html = '<div class="form-group">';
                                html += '<label class="col-md-4 control-label">名稱</label>';
                                html += '<div class="col-md-4 inputGroupContainer">';
                                html += '<div class="input-group">';
                                html += '<input type="text" class="form-control"  name="add_welfare_type_names[]"\n' +
                                    'placeholder="請輸入類別名稱">';
                                html += '</div></div></div>';
                                $('#dynamic_add_field').append(html);
                            }
                        </script>


                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <a onclick="add_input_field()" class="btn btn-sm btn-success">新增類別</a>
                                <br>
                                <br>
{{--                                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;--}}
                                <a class="btn btn-danger" href="{{ URL::previous() }}">取消</a>
                                <button type="submit" class="btn btn-primary">確認送出</button>
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
