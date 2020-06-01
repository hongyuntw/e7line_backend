@extends('layouts.master')

@section('title', '新增任務')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                新增任務
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('tasks.index')}}"><i class="fa fa-shopping-bag"></i> 任務清單</a></li>
                <li class="active">新增任務</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">

                <form class="well form-horizontal" action="{{route('tasks.store')}}" method="post">
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
                            <label class="col-md-4 control-label">任務主題</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-tasks"></i></span>
                                    <textarea class="form-control" name="topic" >{{old('topic')}}</textarea>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">任務內容</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-tasks"></i></span>
                                    <textarea class="form-control" name="content" >{{old('content')}}</textarea>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">指派業務</label>
                            <div class="col-md-4 selectContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <select id="user_select" name="user_ids[]" class="form-control" multiple>
                                        @foreach($users as $user)
                                            @if($user->is_left == 0 && $user->id > 1 && $user->level != 2)
                                            <option value="{{ $user->id}}"> {{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <script>
                                        $(function () {
                                            $("#user_select").attr("size",$("#user_select option").length);
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>





                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <br>
                                <br>
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
