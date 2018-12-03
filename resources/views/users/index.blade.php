@extends('layouts.master')

@section('title', '管理者列表')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                管理者
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 管理者</a></li>
                <li class="active">管理者列表</li>
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
                            <h3 class="box-title">全站管理者一覽表</h3>
                            <div class="box-tools">
                                @if(Auth::user()->level==0)
                                    <td><a class="btn btn-success btn-sm" href="{{ route('users.create') }}">新增管理者</a>
                                    </td>
                                @endif
                                {{--<td><a hidden class="btn btn-success btn-sm" href="{{ route('users.create') }}">新增管理者</a></td>--}}

                            </div>
                            {{--<div class="box-tools">--}}
                            {{--<a class="btn btn-success btn-sm" href="{{ route('products.create') }}">新增管理者</a>--}}
                            {{--</div>--}}
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    <th class="text-center" style="width: 10px;">id</th>
                                    <th class="text-center" style="width: 70px">管理者名稱</th>
                                    <th class="text-center" style="width: 70px">管理者帳號</th>
                                    <th class="text-center" style="width: 70px">註冊日期</th>
                                    <th class="text-center" style="width: 120px">管理者等級</th>
                                    @if(Auth::user()->level==0||Auth::user()->level==2)

                                        <th class="text-center" style="width: 120px">權限管理</th>

                                    @endif
                                </tr>
                                @foreach ($users as $user)
                                    <tr class="text-center">
                                        <td>{{ $user->id }}.</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email}}</td>
                                        <td>{{ $user->created_at }}</td>

                                        @if($user->level==0)
                                            <td>有權限</td>
                                        @elseif($user->level==1)
                                            <td>無權限</td>
                                        @elseif($user->level==2)
                                            <td>老大</td>
                                        @endif
                                        @if((Auth::user()->level==0||Auth::user()->level==2)&&$user->id!=Auth::user()->id)
                                            @if($user->level!=2)
                                                <td class="text-center">
                                                    <form action="{{ route('users.levelup', $user->id) }}" method="post"
                                                          style="display: inline-block">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-primary">提升權限
                                                        </button>
                                                    </form>
                                                    @if(Auth::user()->level==2)
                                                        <form action="{{ route('users.leveldown', $user->id) }}"
                                                              method="post"
                                                              style="display: inline-block">
                                                            @csrf
                                                            <button type="submit" class="btn btn-xs btn-danger">降低權限
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $users->links()}}
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
