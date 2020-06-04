@extends('layouts.master')

@section('title', '編輯業務資訊')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                編輯業務資訊
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('users.index')}}"><i class="fa fa-shopping-bag"></i> 業務資料</a></li>
                <li class="active">編輯業務資訊</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">

                <form class="well form-horizontal" action="{{ route('users.update', $user->id) }}" method="post"
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
                            <label class="col-md-4 control-label">業務名稱</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="title" name="name" placeholder="請輸入名稱"
                                           value="{{ old('name', $user->name) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">電話</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                                    <input type="text" class="form-control"name="phone_number" placeholder="請輸入電話"
                                           value="{{ old('phone_number',$user->phone_number) }}" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">分機</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                                    <input type="text" class="form-control"name="extension_number" placeholder="請輸入分機"
                                           value="{{ old('extension_number',$user->extension_number) }}" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">新密碼</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="請輸入密碼">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">再次輸入新密碼</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                                    <input type="password" class="form-control" name="password_confirmation"
                                           placeholder="請再次輸入密碼">
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->level==2)
                            <div class="form-group">
                                <label class="col-md-4 control-label">Level</label>
                                <div class="col-md-4 selectContainer">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>

                                        <select id="level" name="level" class="form-control">
                                            <option
                                                value="0" {{ old('level',$user->level) == 0 ? 'selected' : '' }}>
                                                業務
                                            </option>
                                            <option
                                                value="1" {{ old('level',$user->level) == 1 ? 'selected' : '' }}>
                                                採購
                                            </option>
                                            <option
                                                value="2" {{ old('level',$user->level) == 2 ? 'selected' : '' }}>
                                                Root
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">狀態</label>
                                <div class="col-md-4 selectContainer">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>

                                        <select id="is_left" name="is_left" class="form-control">
                                            <option
                                                value="0" {{ old('is_left',$user->is_left) == 0 ? 'selected' : '' }}>
                                                正常
                                            </option>
                                            <option
                                                value="1" {{ old('is_left',$user->is_left) == 1 ? 'selected' : '' }}>
                                                失效
                                            </option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                    @endif


                    <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <a class="btn btn-danger" href="{{ URL::previous() }}">取消</a>
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
