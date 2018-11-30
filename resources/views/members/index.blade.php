@extends('layouts.master')

@section('title', '會員列表')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                會員管理
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 會員管理</a></li>
                <li class="active">會員列表</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">全站會員一覽表</h3>

                            {{--<div class="box-tools">--}}
                            {{--<a class="btn btn-success btn-sm" href="{{ route('products.create') }}">新增會員</a>--}}
                            {{--</div>--}}
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" style="width: 10px;">id</th>
                                    <th class="text-center" style="width: 70px">會員名稱</th>
                                    <th class="text-center" style="width: 70px">會員帳號</th>
                                    <th class="text-center" style="width: 70px">註冊日期</th>
                                    <th class="text-center" style="width: 120px">管理功能</th>
                                </tr>
                                @foreach ($members as $member)
                                    <tr>
                                        <td>{{ $member->id }}.</td>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->email}}</td>
                                        <td>{{ $member->created_at }}</td>

                                        <td class="text-center">
                                            <a href="{{ route('members.edit', $member->id) }}"
                                               class="btn btn-xs btn-primary">編輯</a>
                                            <form action="{{ route('members.destroy', $member->id) }}" method="post"
                                                  style="display: inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger">刪除</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $members->links()}}
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
