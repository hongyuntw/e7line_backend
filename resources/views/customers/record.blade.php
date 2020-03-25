@extends('layouts.master')

@section('title', '客戶紀錄')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 客戶紀錄</a></li>
                <li class="active">客戶紀錄</li>
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
                        <!-- /.box-header -->
                        <div class="box-body">
                            <h3 class="text-center">客戶資訊</h3>
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    <th class="text-center" style="width: 10px;">客戶名稱</th>
                                    <th class="text-center" style="width: 10px;">聯絡電話</th>
                                </tr>
                                <tr>
                                    <td class="text-center">{{$customer->name}}</td>
                                    <td class="text-center">{{$customer->phone_number}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="box-body">
                            <h3 class="text-center">客戶聯絡人資訊</h3>
                            <table class="table table-bordered">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 10px;">聯絡人姓名</th>
                                        <th class="text-center" style="width: 10px;">聯絡電話</th>
                                        <th class="text-center" style="width: 10px;">分機</th>
                                        <th class="text-center" style="width: 10px;">聯絡信箱</th>
                                    </tr>
                                @foreach ($business_concat_persons as $concat_person)
                                    <tr class="text-center">
                                        <td>{{ $concat_person->name}}</td>
                                        <td>{{ $concat_person->phone_number }}</td>
                                        <td>{{ $concat_person->extension_number}}</td>
                                        <td>{{ $concat_person->email }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="box-body">
                            <h3 class="text-center">福利資訊</h3>
                            <table class="table table-bordered">
                                <tr class="text-center">
                                    <th class="text-center" style="width: 10px;">name</th>
                                </tr>
                                @foreach ($welfarestatus as $welfare_st)
                                    <tr class="text-center">
                                        <td>{{ $welfare_st->welfare->name}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
{{--                            {{ $members->links()}}--}}
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
