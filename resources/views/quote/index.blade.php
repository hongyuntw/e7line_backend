@extends('layouts.master')

@section('title', '報價')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                報價
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('quote.index')}}"><i class="fa fa-shopping-bag"></i> 報價參考</a></li>
                <li class="active">報價列表</li>
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
                                <div class="col-md-6">
                                    <label>商品選擇</label>

                                    <select id="product" name="product_id">
                                        <option value="-1">無</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                    <script>
                                        var product_select = $("#product").selectize({
                                            onChange : function(value){
                                                console.log(value);
                                                var node = document.getElementById("dynamicQuote");
                                                if(value>0){
                                                    $.ajax({
                                                        type: "get",
                                                        url: "{{route('quote.getProductQuote')}}",
                                                        data: {
                                                            id: value
                                                        },
                                                        success: function (msg) {
                                                            node.innerHTML = '';
                                                            node.innerHTML = msg;

                                                        }
                                                    })

                                                }
                                                else{
                                                    node.innerHTML = '';
                                                }


                                            }
                                        });
                                    </script>
                                </div>
                                <div class="col-md-6">
                                    <label>其他功能</label>
                                    <br>
                                    <a href="{{route('quote.create')}}" class="btn btn-primary">新增報價參考</a>
                                </div>
                            </div>
                        </div>
                        <div class="box-body" id="dynamicQuote">
{{--                                <table class="table table-bordered table-hover" width="100%">--}}
{{--                                    <thead style="background-color: lightgray">--}}
{{--                                    <tr>--}}
{{--                                        <th class="text-center" style="width:15%">級距</th>--}}
{{--                                        <th class="text-center" style="width:20%">原廠%</th>--}}
{{--                                        <th class="text-center" style="width:8%">e7line%</th>--}}
{{--                                        <th class="text-center" style="width:8%">備註</th>--}}
{{--                                        <th class="text-center" style="width:20%">Other</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    @foreach ($quotes as $quote)--}}
{{--                                        @if($quote->is_deleted)--}}
{{--                                            @continue--}}
{{--                                        @endif--}}
{{--                                        <tr ondblclick="" class="text-center">--}}
{{--                                            <td>{{$quote->step}}</td>--}}
{{--                                            <td>{{$quote->origin}}</td>--}}
{{--                                            <td>{{ ($quote->e7line)}}</td>--}}
{{--                                            <td>{{$quote->note}}</td>--}}
{{--                                            <td>--}}
{{--                                                <a class="btn btn-xs btn-primary">編輯</a>--}}

{{--                                                @if( Auth::user()->level==2)--}}
{{--                                                    <form action="{{route('quote.delete',$quote->id)}}"--}}
{{--                                                          method="post"--}}
{{--                                                          style="display: inline-block">--}}
{{--                                                        @csrf--}}
{{--                                                        <button type="submit" class="btn btn-xs btn-danger"--}}
{{--                                                                onclick="return confirm('確定是否刪除')">刪除--}}
{{--                                                        </button>--}}
{{--                                                    </form>--}}
{{--                                                @endif--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                </table>--}}


                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section>
            <div class="container well">
                <div class="col-md-6">
                    <h3>報價參考</h3>
                </div>
                <script>
                    var liveChart;
                    window.onload = function () {
                        var ctx_live = document.getElementById("mychart");
                        liveChart = new Chart(ctx_live, {
                            // 參數設定[註1]
                            type: "line", // 圖表類型
                            data: {
                                labels: ["0"], // 標題
                                datasets: [{
                                    label: "", // 標籤
                                    data: [0], // 資料
                                    borderWidth: 2 // 外框寬度
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: '價錢'
                                        },
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            callback: function (value) {
                                                if (Number.isInteger(value)) {
                                                    return value;
                                                }
                                            },
                                        },
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: '成交數量'
                                        }
                                    }]
                                }
                            }
                        });


                    };

                    function productOnchange(select) {
                        var product_relation_id = select.options[select.selectedIndex].value;
                        if (product_relation_id > 0) {
                            $.ajax({
                                type: "get",
                                url: "{{route('quote.getChartData')}}",
                                data: {
                                    product_relation_id: product_relation_id
                                },
                                success: function (msg) {
                                    console.log(msg);
                                    liveChart.data.labels = msg.prices;
                                    liveChart.data.datasets[0].data = msg.quantity;
                                    liveChart.data.datasets[0].label = msg.title + " 成交數量";
                                    liveChart.update();

                                }
                            })
                        }
                    }


                </script>

                <div class="col-md-6">
                    <select name="product" id="product_select" onchange="productOnchange(this)">
                        <option value="-1">選擇一個商品</option>
                        @foreach($product_relations as $product_relation)
                            <option
                                value="{{$product_relation->id}}">{{$product_relation->product->name}}
                                &nbsp{{$product_relation->product_detail->name}}</option>
                        @endforeach
                    </select>
                    <script>
                        var product_select = $("#product_select").selectize();
                        product_select[0].selectize.setValue("-1");

                    </script>
                </div>
                <div class="col-md-12 center-block" id="chart_div">
                    <canvas id="mychart" width="400" height="200"></canvas>
                </div>


            </div>
        </section>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
