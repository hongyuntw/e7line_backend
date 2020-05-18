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
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-shopping-bag"></i> 首頁</a></li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <script>
                function page(page) {
                    $.ajax({
                        type: "get",
                        url: "{{route('dashboard.setPageSession')}}",
                        data: {
                            page: page
                        },
                        success: function (msg) {
                            console.log(msg);
                        }
                    });
                    $.ajax({
                        type: "get",
                        url: "ajax/getPage",
                        data: {
                            page: page
                        },
                        success: function (msg) {
                            if (msg) {
                                // console.log(msg);
                                const myNode = document.getElementById("dynamic_page");
                                myNode.innerHTML = '';
                                $("#dynamic_page").append(msg);
                            }
                        }
                    })
                }

                function opage(page){
                    $.ajax({
                        type: "get",
                        url: "{{route('dashboard.setPageSession')}}",
                        data: {
                            opage: page
                        },
                        success: function (msg) {
                            console.log(msg);
                        }
                    });
                    var orderBy_select  = document.getElementById("orderBy");
                    var orderBy = orderBy_select.options[orderBy_select.selectedIndex].value;
                    // console.log(orderBy);
                    $.ajax({
                        type: "get",
                        url: "ajax/getoPage",
                        data: {
                            opage: page,
                            orderBy: orderBy
                        },
                        success: function (msg) {
                            if (msg) {
                                const myNode = document.getElementById("dynamic_opage");
                                myNode.innerHTML = '';
                                $("#dynamic_opage").append(msg);
                            }
                        }
                    })

                }

                function wpage(page) {
                    $.ajax({
                        type: "get",
                        url: "{{route('dashboard.setPageSession')}}",
                        data: {
                            wpage: page
                        },
                        success: function (msg) {
                            console.log(msg);
                        }
                    });
                    $.ajax({
                        type: "get",
                        url: "ajax/getwPage",
                        data: {
                            wpage: page
                        },
                        success: function (msg) {
                            if (msg) {
                                // console.log(msg);
                                const myNode = document.getElementById("dynamic_wpage");
                                myNode.innerHTML = '';
                                $("#dynamic_wpage").append(msg);
                            }
                        }
                    })
                }
            </script>

            {{--    待追蹤客戶--}}
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        <div class="box-header with-border text-center">
                            <h3 class="box-title text-center">待追蹤客戶</h3>
                            <div class="box-tools">
                                <button onclick="change_record_status_btn_onclick()" type="button"
                                        id="change_record_status_btn" class="text-right btn btn-success btn-sm">
                                    更改已完成
                                </button>
                            </div>
                        </div>
                        <script>

                            function change_record_status_btn_onclick() {
                                var inputs = document.getElementsByName("change_record_status");
                                var ids = [];
                                for (var i = 0; i < inputs.length; i++) {
                                    if (inputs[i].checked ? 1 : 0) {
                                        ids.push(inputs[i].id);
                                    }
                                }
                                var currpage = document.getElementById("current_customer_page").value;
                                console.log(currpage);
                                // console.log(data);
                                $.ajax({
                                    type: "POST",
                                    url: '/ajax/set_concat_status',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                    },
                                    data: {
                                        ids: ids,
                                        page: currpage,
                                    },
                                    success: function (msg) {
                                        page(msg);
                                    }
                                });

                            }
                        </script>


                        <!-- /.box-header -->
                        <div class="box-body">
                            <form method="post" id="change_record_status_form">

                                <div id="dynamic_page">
                                    <input hidden type="text" id="current_customer_page" value="{{$page}}">


                                    <table class="table table-bordered table-hover" style="width: 100%">
                                        <thead style="background-color: lightgray">
                                        <tr>
                                            <th class="text-center" style="width: 5%">狀態</th>
                                            <th class="text-center" style="width: 30%">客戶名稱</th>
                                            <th class="text-center" style="width: 15%">Email</th>
                                            <th class="text-center" style="width: 15%">Concat Info</th>
                                            <th class="text-center" style="width: 15%">追蹤時間</th>
                                            <th class="text-center" style="width: 30%">Note</th>
                                        </tr>
                                        </thead>
                                        @foreach ($customers as $customer)
                                            <tr class="text-center" ondblclick="">
                                                <td><input type="checkbox" id="{{$customer->concat_record_id}}"
                                                           name="change_record_status">
                                                </td>
                                                <td ondblclick="window.location.href = '/customers/' + {{$customer->customer_id}} + '/record'">
                                                    {{ $customer->customer_name }}</td>
                                                <td ondblclick="window.location.href = '/customers/' + {{$customer->customer_id}} + '/record#ConcatWindow'">
                                                    @if(\App\BusinessConcatPerson::where('customer_id','=',$customer->customer_id)->orderBy('update_date','DESC')->first())
                                                        @if(\App\BusinessConcatPerson::where('customer_id','=',$customer->customer_id)->orderBy('update_date','DESC')->first()->email)
                                                            {{ \App\BusinessConcatPerson::where('customer_id','=',$customer->customer_id)->orderBy('update_date','DESC')->first()->email }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td ondblclick="window.location.href = '/customers/' + {{$customer->customer_id}} + '/record#ConcatWindow'">
                                                    @if(\App\BusinessConcatPerson::where('customer_id','=',$customer->customer_id)->orderBy('update_date','DESC')->first())
                                                        @if(\App\BusinessConcatPerson::where('customer_id','=',$customer->customer_id)->orderBy('update_date','DESC')->first()->phone_number)
                                                            {{ \App\BusinessConcatPerson::where('customer_id','=',$customer->customer_id)->orderBy('update_date','DESC')->first()->phone_number }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($customer->track_date)
                                                        {{date('Y-m-d H:m',strtotime($customer->track_date))}}
                                                    @endif
                                                </td>

                                                <td ondblclick="window.location.href = '/customers/' + {{$customer->customer_id}} + '/record#Development_Record'">
                                                    {{ $customer->track_content }} </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="page">
                                        <!-------分页---------->
                                        @if($count > $rev)
                                            <ul class="pagination">
                                                @if($page !=1)
                                                    <li>
                                                        <a href="javascript:void(0)" onclick="page({{$prev}})"><<</a>
                                                    </li>
                                                @endif
                                                @php($flag = true)
                                                @foreach($pp as $k=>$v)
                                                    {{--                                                當前page--}}
                                                    @if($v == $page)
                                                        <li class="active"><span>{{$v}}</span></li>
                                                        {{--                                                    超過範圍之外page--}}
                                                    @elseif(abs($v-$page)>=3 && $v<$page)
                                                        @if($v==1)
                                                            <li>
                                                                <a href="javascript:void(0)"
                                                                   onclick="page({{$v}})">{{$v}}</a>
                                                            </li>
                                                        @else
                                                            @if($flag)
                                                                <li><span>...</span></li>
                                                                @php($flag = false)
                                                            @endif
                                                        @endif

                                                    @elseif(abs($v-$page)>=3 && $v>$page)
                                                        @if($v==count($pp))
                                                            <li>
                                                                <a href="javascript:void(0)"
                                                                   onclick="page({{$v}})">{{$v}}</a>
                                                            </li>
                                                        @else
                                                            @if($flag)
                                                                <li><span>...</span></li>
                                                                @php($flag = false)
                                                            @endif
                                                        @endif

                                                    @else
                                                        @php($flag = true)
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                               onclick="page({{$v}})">{{$v}}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                                <li>
                                                    <a href="javascript:void(0)" onclick="page({{$next}})">>></a>
                                                </li>
                                            </ul>
                                    @endif
                                    <!-------分页---------->
                                    </div>

                                </div>
                            </form>


                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->


            {{--    待追蹤福利--}}
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border text-center">
                            <h3 class="box-title text-center">待追蹤福利</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="dynamic_wpage">

                                <table class="table table-bordered table-hover">
                                    <thead style="background-color: lightgray">
                                    <tr>
                                        <th class="text-center" style="width: 150px">客戶名稱</th>
                                        <th class="text-center" style="width: 150px">福利目的</th>
                                        <th class="text-center" style="width: 150px">福利類別</th>
                                        <th class="text-center" style="width: 120px">預算</th>
                                    </tr>
                                    </thead>
                                    @foreach ($welfare_statuses as $welfare_status)

                                        <tr class="text-center"
                                            ondblclick="window.location.href='/welfare_status/'+ {{$welfare_status->id}} + '/edit' ">
                                            <td>{{ $welfare_status->customer_name }}</td>
                                            <td>{{ $welfare_status->welfare_name }}</td>
                                            <td>
                                                @foreach($welfare_status->welfare_types as $welfare_type)
                                                    <li>{{$welfare_type->welfare_type_name->name}}</li>
                                                @endforeach

                                            </td>

                                            <td>{{ $welfare_status->budget }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                <div class="page">
                                    <!-------分页---------->
                                    @if($wcount > $wrev)
                                        <ul class="pagination">
                                            @if($wpage !=1)
                                                <li>
                                                    <a href="javascript:void(0)" onclick="wpage({{$wprev}})"><<</a>
                                                </li>
                                            @endif
                                            @php($flag_w = true)
                                            @foreach($wpp as $k=>$v)
                                                {{--                                                當前page--}}
                                                @if($v == $wpage)
                                                    <li class="active"><span>{{$v}}</span></li>
                                                    {{--                                                    超過範圍之外page--}}
                                                @elseif(abs($v-$wpage)>=3 && $v<$wpage)
                                                    @if($v==1)
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                               onclick="wpage({{$v}})">{{$v}}</a>
                                                        </li>
                                                    @else
                                                        @if($flag_w)
                                                            <li><span>...</span></li>
                                                            @php($flag_w = false)
                                                        @endif
                                                    @endif

                                                @elseif(abs($v-$wpage)>=3 && $v>$wpage)
                                                    @if($v==count($wpp))
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                               onclick="wpage({{$v}})">{{$v}}</a>
                                                        </li>
                                                    @else
                                                        @if($flag_w)
                                                            <li><span>...</span></li>
                                                            @php($flag_w = false)
                                                        @endif
                                                    @endif

                                                @else
                                                    @php($flag_w = true)
                                                    <li>
                                                        <a href="javascript:void(0)" onclick="wpage({{$v}})">{{$v}}</a>
                                                    </li>
                                                @endif

                                            @endforeach
                                            <li>
                                                <a href="javascript:void(0)" onclick="wpage({{$wnext}})">>></a>
                                            </li>
                                        </ul>
                                @endif
                                <!-------分页---------->
                                </div>

                            </div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        <div class="box-header with-border text-center">
                            <h3 class="box-title text-center">可能的訂單</h3>
                            <div class="box-tools" style="display: inline-block">
                                <label>order By</label>
                                <select id="orderBy" name="orderBy" class="" onchange="opage({{$opage}})">
                                    <option value="create_date">建單時間</option>
                                    <option value="welfare_id">目的</option>
                                    <option value="customer_id">客戶名稱</option>
                                    <option value="amount">總金額</option>
                                </select>
                            </div>
                        </div>


                        <!-- /.box-header -->
                        <div class="box-body">
                            <form method="post" id="change_record_status_form">

                                <div id="dynamic_opage">
                                    <input hidden type="text" id="current_order_page" value="{{$opage}}">


                                    <table class="table table-bordered table-hover" style="width: 100%">
                                        <thead style="background-color: lightgray">
                                        <tr>
                                            <th class="text-center" style="width: 30%">客戶名稱</th>
                                            <th class="text-center" style="width: 15%">建單時間</th>
                                            <th class="text-center" style="width: 15%">目的</th>
                                            <th class="text-center" style="width: 15%">總金額</th>
                                            <th class="text-center" style="width: 30%">其他</th>
                                        </tr>
                                        </thead>
                                        @foreach ($orders as $order)
                                            <tr class="text-center"
                                                ondblclick="window.location.href = '/orders/' + {{$order->id}} + '/detail'">
                                                {{--                                                <td><input type="checkbox" id="{{$customer->concat_record_id}}"--}}
                                                {{--                                                           name="change_record_status">--}}
                                                {{--                                                </td>--}}
                                                <td>
                                                    @if($order->customer)
                                                        {{ $order->customer->name }}</td>
                                                    @else
                                                        {{$order->other_customer_name}}
                                                    @endif
                                                <td>
                                                    {{date('Y-m-d H:m',strtotime($order->create_date))}}
                                                </td>
                                                <td>
                                                    {{$order->welfare->welfare_name}}
                                                </td>

                                                <td>
                                                    {{$order->amount}}
                                                </td>
                                                <td>
                                                    其他
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="page">
                                        <!-------分页---------->
                                        @if($ocount > $orev)
                                            <ul class="pagination">
                                                @if($opage !=1)
                                                    <li>
                                                        <a href="javascript:void(0)" onclick="opage({{$oprev}})"><<</a>
                                                    </li>
                                                @endif
                                                @php($flago = true)
                                                @foreach($opp as $k=>$v)
                                                    {{--                                                當前page--}}
                                                    @if($v == $opage)
                                                        <li class="active"><span>{{$v}}</span></li>
                                                        {{--                                                    超過範圍之外page--}}
                                                    @elseif(abs($v-$opage)>=3 && $v<$opage)
                                                        @if($v==1)
                                                            <li>
                                                                <a href="javascript:void(0)"
                                                                   onclick="opage({{$v}})">{{$v}}</a>
                                                            </li>
                                                        @else
                                                            @if($flago)
                                                                <li><span>...</span></li>
                                                                @php($flago = false)
                                                            @endif
                                                        @endif

                                                    @elseif(abs($v-$opage)>=3 && $v>$opage)
                                                        @if($v==count($opp))
                                                            <li>
                                                                <a href="javascript:void(0)"
                                                                   onclick="opage({{$v}})">{{$v}}</a>
                                                            </li>
                                                        @else
                                                            @if($flago)
                                                                <li><span>...</span></li>
                                                                @php($flago = false)
                                                            @endif
                                                        @endif

                                                    @else
                                                        @php($flago = true)
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                               onclick="opage({{$v}})">{{$v}}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                                <li>
                                                    <a href="javascript:void(0)" onclick="opage({{$onext}})">>></a>
                                                </li>
                                            </ul>
                                    @endif
                                    <!-------分页---------->
                                    </div>

                                </div>
                            </form>


                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection






