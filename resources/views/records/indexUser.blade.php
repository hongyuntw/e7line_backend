@extends('layouts.master')

@section('title', '業務開發紀錄')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                報表
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('record.index')}}"><i class="fa fa-shopping-bag"></i> 業務開發紀錄</a></li>
            </ol>

        </section>


        <!-- Main content -->
        <section class="content container-fluid">


            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="row">
                <div class="col-md-12">
                    <div class="box primary">
                        <div class="box-header with-border">
                            <div class="row">

                                <form name="filter_form" action="{{route('record.index')}}">

                                    <div class="col-md-4">
                                        <label>From</label>
                                        <input type="date" class="form-control" name="date_from" id="date_from"
                                               value="@if($date_from != null){{($date_from)}}@endif">

                                    </div>

                                    <div class="col-md-4">
                                        <label>To</label>
                                        <input type="date" class="form-control" name="date_to" id="date_to"
                                               value="@if($date_to != null){{$date_to}}@endif">
                                        <button type="submit" style="width: 100%">篩選</button>


                                    </div>


                                </form>

                            </div>
                        </div>
                        <section>
                            <table class="table table-bordered table-hover" width="100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th class="text-center" style="width:25%">客戶名稱</th>
                                    <th class="text-center" style="width:7%">方法</th>

                                    <th class="text-center" style="width:20%">開發內容</th>
                                    <th class="text-center" style="width:25%">追蹤內容/日期</th>

                                    <th class="text-center" style="width:10%">建立日期</th>
                                    <th class="text-center" style="width: 5%">其他</th>


                                </tr>
                                </thead>




                                @foreach ($records as $record)


                                    <tr  class="text-center">

                                        <td class="align-middle " style="vertical-align: middle">{{ $record->customer->name  }}</td>
                                        <td class="align-middle " style="vertical-align: middle">{{ $record->method  }}</td>
                                        <td class="align-middle " style="vertical-align: middle">{{ $record->development_content}} </td>
                                        <td class="align-middle " style="vertical-align: middle">
                                            {{$record->track_content}}
                                            <br>
                                            <br>
                                            <i>
                                                {{$record->track_date}}
                                            </i>
                                        </td>
                                        <td class="align-middle " style="vertical-align: middle">
                                            {{$record->create_date}}
                                        </td>
                                        <td class="align-middle " style="vertical-align: middle">
                                            <a href="{{route('record.show_record',$record->id)}}"
                                               class="btn btn-xs btn-primary">show</a>
                                        </td>


                                    </tr>
                                @endforeach


                            </table>

                            <div class="box-footer clearfix">
                                {{ $records->appends(request()->input())->links() }}
                            </div>

                        </section>

                    </div>

                </div>
            </div>
        </section>


        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
