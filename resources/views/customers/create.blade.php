@extends('layouts.master')

@section('title', '新增客戶資訓')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                新增客戶資訓
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 客戶管理</a></li>
                <li class="active">新增客戶資訊</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="row">
                <!-- .col -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">新增客戶資訊</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="{{ route('customers.store') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="box-body">
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
                                    <label for="title">名稱</label>
                                    <input type="text" class="form-control" id="title" name="name" placeholder="請輸入名稱"
                                           value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label for="user_id">負責業務員</label>
                                    <select id="user_id" name="user_id" class="form-control">
                                        @foreach($users as $user)
                                            <option
                                                value="{{ $user->id }}"{{ (old('$user_id',$user->id) == Auth::user()->id)? ' selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tax_id">統編</label>
                                    <input type="text" class="form-control" id="tax_id" name="tax_id"
                                           placeholder="請輸入統編" value="{{ old('tax_id') }}">
                                </div>
                                <div class="form-group">
                                    <label for="capital">capital</label>
                                    <input type="number" class="form-control" id="capital" name="capital"
                                           placeholder="請輸入資本額" value="{{ old('capital') }}">
                                </div>
                                <div class="form-group">
                                    <label for="scales">規模</label>
                                    <input type="number" class="form-control" id="scales" name="scales"
                                           placeholder="請輸入規模"
                                           value="{{ old('scales') }}">
                                </div>
                                <div class="form-group">
                                    <label for="city">縣市</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="請輸入縣市"
                                           value="{{ old('city') }}">
                                </div>
                                <div class="form-group">
                                    <label for="area">地區</label>
                                    <input type="text" class="form-control" id="area" name="area" placeholder="請輸入地區"
                                           value="{{ old('area') }}">
                                </div>

                                <div class="form-group">
                                    <label for="address">地址</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                           placeholder="請輸入地址"
                                           value="{{ old('address') }}">
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">電話</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                                           placeholder="請輸入電話"
                                           value="{{ old('phone_number') }}">
                                </div>
                                <div class="form-group">
                                    <label for="fax_number">傳真</label>
                                    <input type="text" class="form-control" id="fax_number" name="fax_number"
                                           placeholder="請輸入傳真"
                                           value="{{ old('fax_number') }}">
                                </div>
                                <div class="form-group">
                                    <label for="status">狀態</label>
                                    <select id="status" name="status" class="form-control">
                                        @foreach( [1,2,3,4,5] as $st_id)
                                            <option
                                                value="{{ $st_id }}"{{ (old('st_id') == $st_id)? ' selected' : '' }}>{{ $status_text[$st_id] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="active_status">是否開通</label>
                                    <select id="active_status" name="active_status" class="form-control">
                                        <option value="0" {{ old('$active_status') == 0 ? 'selected' : '' }}>
                                            否
                                        </option>
                                        <option value="1" {{ old('$active_status') == 1 ? 'selected' : '' }}>
                                            是
                                        </option>

                                    </select>
                                </div>


                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer text-right">
                                <a class="btn btn-link" href="{{route('customers.index')}}">取消</a>
                                <button type="submit" class="btn btn-primary">新增</button>
                            </div>
                        </form>
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
