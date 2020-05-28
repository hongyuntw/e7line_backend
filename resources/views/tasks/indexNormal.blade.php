@extends('layouts.master')

@section('title', '任務列表')

@section('content')
    <meta id="csrf_token" name="csrf_token" content="{{ csrf_token() }}"/>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                任務列表
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('tasks.index')}}"><i class="fa fa-shopping-bag"></i> 任務清單</a></li>
                <li class="active">任務列表</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            <div class="tabs">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <!-- /.box-header -->
                            <div class="box-body">

                                <div id="Customer">
                                    <h4 class="text-center">
                                        <label style="font-size: medium">未完成</label>
                                    </h4>

                                </div>


                                <table class="table table-striped" style="width: 100%">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 10%;">主題</th>

                                        <th class="text-center" style="width: 10%;">任務內容</th>
                                        <th class="text-center" style="width: 20%;">訊息</th>
                                        <th class="text-center" style="width: 20%;">回覆／完成任務</th>

                                    </tr>
                                    </thead>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center">

                                        </td>
                                        <td class="text-center">

                                        </td>

                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--                            Order Note--}}
            <div class="tabs">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">

                            <div class="box-body">
                                <h4 class="text-center">
                                    <label style="font-size: medium">已處理，待確認</label>
                                </h4>


                                <table class="table table-striped" style="width: 100%">
                                    <thead style="background-color: lightgray">
                                    <tr class="text-center">
                                        <th class="text-center" style="width: 10%;">主題</th>

                                        <th class="text-center" style="width: 10%;">任務內容</th>
                                        <th class="text-center" style="width: 20%;">訊息</th>
                                        <th class="text-center" style="width: 20%;">回覆／完成任務</th>

                                    </tr>
                                    </thead>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center">

                                        </td>
                                        <td class="text-center">

                                        </td>

                                    </tr>
                                </table>


                            </div>
                        </div>
                    </div>
                </div>

                {{--                            Order Items--}}

                <div class="tabs">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">


                                <div class="box-body">
                                    <div id="Development_Record">
                                        <h4 class="text-center">
                                            <label style="font-size: medium">完成</label>
                                        </h4>
                                    </div>
                                    <table class="table table-striped" style="width: 100%">
                                        <thead style="background-color: lightgray">
                                        <tr class="text-center">
                                            <th class="text-center" style="width: 10%;">主題</th>

                                            <th class="text-center" style="width: 10%;">任務內容</th>
                                            <th class="text-center" style="width: 20%;">訊息</th>
                                            <th class="text-center" style="width: 20%;">回覆／完成任務</th>

                                        </tr>
                                        </thead>
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-center">

                                            </td>
                                            <td class="text-center">

                                            </td>

                                        </tr>
                                    </table>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
