@extends('layouts.master')

@section('title', '意見列表')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                意見管理
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 意見管理</a></li>
                <li class="active">意見列表</li>
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
                            <h3 class="box-title">全站意見一覽表</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" style="width: 10px;">ID</th>
                                    <th class="text-center" style="width: 50px">來信者信箱</th>
                                    <th class="text-center" style="width: 50px">來信者姓名</th>
                                    <th class="text-center" style="width: 150px">內容</th>
                                    <th class="text-center" style="width: 50px">狀態</th>
                                    <th class="text-center" style="width: 120px">管理功能</th>
                                </tr>
                                @foreach ($suggestions as $suggestion)
                                    <tr class="text-center">
                                        <td>{{ $suggestion->id }}.</td>
                                        <td>{{ $suggestion->email }}</td>
                                        <td>{{ $suggestion->name }}</td>
                                        <td>{{ $suggestion->text }}</td>
                                        <td>
                                            <span class="{{  ($suggestion->is_replied==0) ? 'label label-warning' : 'label label-success'  }}">
                                                {{  ($suggestion->is_replied==0) ? '未回覆' : '已回覆'  }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('suggestions.edit', $suggestion->id) }}"
                                               class="btn btn-xs btn-primary">回信</a>
                                            {{--<form action="{{ route('suggestions.remove', $suggestion->id) }}" method="post"--}}
                                                  {{--style="display: inline-block">--}}
                                                {{--@csrf--}}
                                                {{--<button type="submit" class="btn btn-xs btn-danger">下架</button>--}}
                                            {{--</form>--}}
                                            {{--<form action="{{ route('suggestions.upup', $suggestion->id) }}" method="post"--}}
                                                  {{--style="display: inline-block">--}}
                                                {{--@csrf--}}
                                                {{--<button type="submit" class="btn btn-xs btn-danger">上架</button>--}}
                                            {{--</form>--}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $suggestions->links() }}
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
