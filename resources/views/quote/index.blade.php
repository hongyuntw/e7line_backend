@extends('layouts.master')

@section('title', '報價')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container well">
                <div class="col-md-6">
                    <h3>Price List</h3>
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
                                url: "{{route('qoute.getChartData')}}",
                                data: {
                                    product_relation_id: product_relation_id
                                },
                                success: function (msg) {
                                    // console.log(msg);
                                    var node = document.getElementById("mychart");
                                    // console.log(liveChart);

                                    // console.log(liveChart.data.labels);
                                    liveChart.data.labels = msg.prices;
                                    // console.log(liveChart.data.labels);
                                    liveChart.data.datasets[0].data = msg.quantity;
                                    liveChart.data.datasets[0].label = msg.title + " 成交數量";
                                    // console.log()
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
                                value="{{$product_relation->id}}">{{$product_relation->product->name}}{{$product_relation->product_detail->name}}</option>
                        @endforeach
                    </select>
                    <script>
                            {{--                        default select--}}
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
