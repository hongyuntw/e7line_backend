@extends('layouts.master')

@section('title', '業務列表')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                業務
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 業務</a></li>
                <li class="active">業務列表</li>
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
                            <h3 class="box-title">業務一覽表</h3>
                            <div class="box-tools">
                                @if(Auth::user()->level==2)
                                    <td><a class="btn btn-success btn-sm" href="{{route('users.create')}}">新增業務</a>
                                    </td>
                                @endif
                                {{--<td><a hidden class="btn btn-success btn-sm" href="{{ route('users.create') }}">新增業務者</a></td>--}}

                            </div>
                            {{--<div class="box-tools">--}}
                            {{--<a class="btn btn-success btn-sm" href="{{ route('products.create') }}">新增業務者</a>--}}
                            {{--</div>--}}
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    {{--                                    <th class="text-center" style="width: 10px;">id</th>--}}
                                    <th class="text-center" style="width: 70px">業務名稱</th>
                                    <th class="text-center" style="width: 70px">業務帳號</th>
                                    <th class="text-center" style="width: 70px">註冊日期</th>
                                    <th class="text-center" style="width: 120px">業務權限</th>


                                    {{--                                    @if(Auth::user()->level==2)--}}

                                    <th class="text-center" style="width: 120px">功能</th>

                                    {{--                                    @endif--}}
                                </tr>
                                @foreach ($users as $user)
                                    <tr class="text-center">
                                        @if($user->id==1)
                                            @continue
                                        @endif
                                        {{--                                        <td>{{ $user->id }}.</td>--}}
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email}}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            @if($user->level==0)
                                                業務
                                            @elseif($user->level==1)
                                                採購
                                            @elseif($user->level==2)
                                                root
                                            @endif

                                            @if($user->is_left==1)
                                                (失效)
                                            @endif
                                        </td>

                                        @if(Auth::user()->level==2 || Auth::user()->id==$user->id)
                                            <td><a class="btn btn-primary"
                                                   href="{{route('users.edit',$user->id)}}">編輯 </a></td>
                                        @endif
                                        {{--                                        @if((Auth::user()->level==0||Auth::user()->level==2)&&$user->id!=Auth::user()->id)--}}
                                        {{--                                            @if($user->level!=2)--}}
                                        {{--                                                <td class="text-center">--}}
                                        {{--                                                    <form action="{{ route('users.levelup', $user->id) }}" method="post"--}}
                                        {{--                                                          style="display: inline-block">--}}
                                        {{--                                                        @csrf--}}
                                        {{--                                                        <button type="submit" class="btn btn-xs btn-primary">提升權限--}}
                                        {{--                                                        </button>--}}
                                        {{--                                                    </form>--}}
                                        {{--                                                    @if(Auth::user()->level==2)--}}
                                        {{--                                                        <form action="{{ route('users.leveldown', $user->id) }}"--}}
                                        {{--                                                              method="post"--}}
                                        {{--                                                              style="display: inline-block">--}}
                                        {{--                                                            @csrf--}}
                                        {{--                                                            <button type="submit" class="btn btn-xs btn-danger">降低權限--}}
                                        {{--                                                            </button>--}}
                                        {{--                                                        </form>--}}
                                        {{--                                                    @endif--}}
                                        {{--                                                </td>--}}
                                        {{--                                            @endif--}}
                                        {{--                                        @endif--}}
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
