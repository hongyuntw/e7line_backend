@extends('layouts.master')

@section('title', '福利狀況')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                福利狀況
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 福利狀況</a></li>
                <li class="active">福利狀況列表</li>
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
                            <h3 class="box-title">福利狀況一覽表</h3>
                            <div class="box-tools">
                                {{--<td><a hidden class="btn btn-success btn-sm" href="{{ route('users.create') }}">新增福利者</a></td>--}}

                            </div>
                            {{--<div class="box-tools">--}}
                            {{--<a class="btn btn-success btn-sm" href="{{ route('products.create') }}">新增福利者</a>--}}
                            {{--</div>--}}
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    <th class="text-center" style="width: 70px">客戶名稱</th>
                                    <th class="text-center" style="width: 70px">福利名稱</th>
                                    <th class="text-center" style="width: 70px">預計金額</th>
                                    <th class="text-center" style="width: 70px">建立日期</th>


                                </tr>
                                @foreach ($welfarestatus as $wf)
                                    <tr class="text-center">
                                        <td>{{ $wf->customer->name }}.</td>
                                        <td>{{ $wf->welfare->name }}</td>
                                        <td>{{ $wf->amount}}</td>
                                        <td>{{ $wf->created_at }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $welfarestatus->links()}}
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
