@extends('layouts.master')

@section('title', '報表')

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
                <li><a href="{{route('report.index')}}"><i class="fa fa-shopping-bag"></i> 業務報表</a></li>
            </ol>

        </section>


        <script>
            function createChart(){
                var date_to = document.getElementById("date_to").value;
                var date_from = document.getElementById("date_from").value;
                if(date_from > date_to){
                    alert("日期選擇錯誤");
                    return;
                }
                //create 業績圖
                $.ajax({
                    type: "get",
                    url: "{{route('report.createTotalAmountChart')}}",
                    data: {
                        date_from: date_from,
                        date_to : date_to,
                    },
                    success: function (res) {
                        console.log(res);
                        var user_names = [];
                        var total_amount = [];
                        for (let [key, value] of Object.entries(res)) {
                            user_names.push(key);
                            total_amount.push(value);
                        }
                        console.log(user_names);
                        console.log(total_amount);
                        console.log(totalAmountChart);
                        totalAmountChart.data.labels = user_names;
                        totalAmountChart.data.datasets[0].data = total_amount;
                        totalAmountChart.data.datasets[0].label = "成交金額";
                        totalAmountChart.update();
                    }
                });
                //聯繫紀錄圖
                $.ajax({
                    type: "get",
                    url: "{{route('report.createRecordCountChart')}}",
                    data: {
                        date_from: date_from,
                        date_to : date_to,
                    },
                    success: function (res) {
                        console.log(res);
                        var user_names = [];
                        var total_amount = [];
                        for (let [key, value] of Object.entries(res)) {
                            user_names.push(key);
                            total_amount.push(value);
                        }
                        console.log(user_names);
                        console.log(total_amount);
                        console.log(concatRecordCountChart);
                        concatRecordCountChart.data.labels = user_names;
                        concatRecordCountChart.data.datasets[0].data = total_amount;
                        concatRecordCountChart.data.datasets[0].label = "聯繫次數";
                        concatRecordCountChart.update();
                    }
                })
                $.ajax({
                    type: "get",
                    url: "{{route('report.createActiveCustomerChart')}}",
                    data: {
                        date_from: date_from,
                        date_to : date_to,
                    },
                    success: function (res) {
                        console.log(res);
                        var user_names = [];
                        var total_amount = [];
                        for (let [key, value] of Object.entries(res)) {
                            user_names.push(key);
                            total_amount.push(value);
                        }
                        console.log(user_names);
                        console.log(total_amount);
                        console.log(activeCustomerCountChart);
                        activeCustomerCountChart.data.labels = user_names;
                        activeCustomerCountChart.data.datasets[0].data = total_amount;
                        activeCustomerCountChart.data.datasets[0].label = "開發客戶數";
                        activeCustomerCountChart.update();
                    }
                })

            }
        </script>


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


                                <div class="col-md-4">
                                    <label>From</label>
                                    <input type="date" class="form-control" name="date_from" id="date_from"
                                           value="@if($date_from != null){{($date_from)}}@endif">
                                    <label>To</label>
                                    <input type="date" class="form-control" name="date_to" id="date_to"
                                           value="@if($date_to != null){{$date_to}}@endif">
                                    <button onclick="createChart()"  style="width: 100%" type="button" >篩選</button>


                                </div>
                                <div class="col-md-4">
                                </div>

                            </div>
                        </div>
                        <section>
                            <div class="container well">
                                <div class="col-md-12">
                                    <h3>業務報表</h3>
                                </div>
                                <script>
                                    var totalAmountChart;
                                    window.onload = function () {
                                        var totalAmountNode = document.getElementById("totalAmountChart");
                                        totalAmountChart = new Chart(totalAmountNode, {
                                            // 參數設定[註1]
                                            type: "bar", // 圖表類型
                                            data: {
                                                labels: ["0"], // 標題
                                                datasets: [{
                                                    label: "", // 標籤
                                                    data: [0], // 資料
                                                    borderWidth: 1 // 外框寬度
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                scales: {
                                                    yAxes: [{
                                                        ticks: {
                                                            beginAtZero: true,
                                                            callback: function(value) {if (value % 1 === 0) {return value;}}

                                                        },
                                                    }]
                                                }
                                            }
                                        });
                                        var concatRecordCountNode = document.getElementById("concatRecordCountChart");
                                        concatRecordCountChart = new Chart(concatRecordCountNode, {
                                            // 參數設定[註1]
                                            type: "bar", // 圖表類型
                                            data: {
                                                labels: ["0"], // 標題
                                                datasets: [{
                                                    label: "", // 標籤
                                                    data: [0], // 資料
                                                    borderWidth: 1 // 外框寬度
                                                }]
                                            },
                                            options: {
                                                maintainAspectRatio: false,
                                                responsive: true,
                                                scales: {
                                                    yAxes: [{
                                                        ticks: {
                                                            beginAtZero: true,
                                                            callback: function(value) {if (value % 1 === 0) {return value;}}

                                                        },
                                                    }]
                                                }
                                            }
                                        });

                                        var activeCustomerCountNode = document.getElementById("activeCustomerCountChart");
                                        activeCustomerCountChart = new Chart(activeCustomerCountNode, {
                                            // 參數設定[註1]
                                            type: "bar", // 圖表類型
                                            data: {
                                                labels: ["0"], // 標題
                                                datasets: [{
                                                    label: "", // 標籤
                                                    data: [0], // 資料
                                                    borderWidth: 1 // 外框寬度
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                scales: {
                                                    yAxes: [{
                                                        ticks: {
                                                            beginAtZero: true,
                                                            callback: function(value) {if (value % 1 === 0) {return value;}}

                                                        },
                                                    }]
                                                }
                                            }
                                        });


                                    };



                                </script>


                                <div class="col-md-6 center-block">
                                    <canvas id="totalAmountChart" ></canvas>
                                </div>
                                <div class="col-md-6 center-block">
                                    <canvas id="concatRecordCountChart"></canvas>
                                </div>
                                <div class="col-md-12 center-block" >
                                    <canvas style="height: 200px !important;" id="activeCustomerCountChart"></canvas>
                                </div>


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
