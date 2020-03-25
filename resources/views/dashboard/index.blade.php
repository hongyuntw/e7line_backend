@extends('layouts.master')

@section('title', '業務列表')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                首頁
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 首頁</a></li>
                <li class="active">主控台</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            {{--    待追蹤客戶--}}
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">待追蹤客戶</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" style="width: 150px">客戶名稱</th>
                                    <th class="text-center" style="width: 150px">Email</th>
                                    <th class="text-center" style="width: 150px">Concat Info</th>
                                    <th class="text-center" style="width: 120px">Status</th>
                                    {{--                            <th class="text-center" style="width: 120px">Notice</th>--}}
                                </tr>
                                @foreach ($customers as $customer)
                                    <tr class="text-center">
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->tax_id }}</td>
                                        <td>{{ $customer->phone_number }}</td>
                                        <td>{{ $customer->status_id }} </td>


                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $customers->links() }}
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






