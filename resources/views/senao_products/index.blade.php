@extends('layouts.master')

@section('title', '神腦商品列表')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <a href="{{route('senao_products.index')}}">神腦商品列表</a>
                <small></small>
            </h1>

        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            @if(session('msg'))
                @if(session('msg')=='')
                    <div class="alert alert-success text-center">{{'Success'}}</div>
                @else
                    <div class="alert alert-danger text-center">{{session('msg')}}</div>
                @endif
            @endif

            @if(session('msgs'))
                <div class="alert-danger alert text-center">
                    @foreach(session('msgs') as $msg)
                        {{$msg}} <br>

                    @endforeach
                </div>
            @endif

            <script>
                function product_change(product_select) {
                    var product_detail_id = '{{$product_detail_id}}';
                    console.log("product_detail_id");
                    console.log(product_detail_id);
                    var product_id = product_select.options[product_select.selectedIndex].value;
                    // console.log(product_id);
                    if (product_id > 0) {
                        $.ajax({
                            url: '/ajax/get_product_details',
                            data: {product_id: product_id}
                        })
                            .done(function (res) {
                                console.log(res);
                                var myNode;
                                myNode = document.getElementById("product_select_div");
                                // console.log(myNode);
                                myNode.innerHTML = '';
                                html = '<select class="form-control" id="product_detail_id" name="product_detail_id">';
                                html += '<option value=-1>請選擇產品</option>';
                                for (let [key, value] of Object.entries(res)) {
                                    html += '<option value=\"' + key + '\">' + value[0] + '</option>'
                                }
                                html += '</select>';
                                // console.log(myNode);
                                myNode.innerHTML = html;

                                var product_detail_select = document.getElementById("product_detail_id");
                                console.log(product_detail_select);
                                for (var i = 0; i < product_detail_select.options.length; i++) {
                                    if (product_detail_select.options[i].value == product_detail_id) {
                                        product_detail_select.selectedIndex = i;
                                        break;
                                    }
                                }
                            })

                    } else {
                        var product_detail_select = document.getElementById("product_detail_id");
                        var i, L = product_detail_select.options.length - 1;
                        for (i = L; i > 0; i--) {
                            product_detail_select.remove(i);
                        }
                        product_detail_select.selectedIndex = 0;


                    }
                }

            </script>

            <!--------------------------
                  | Your Page Content Here |
                  -------------------------->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="row">
                                <form name="filter_form" action="{{route('senao_products.index')}}" method="get">

                                    <div class="col-md-4">
                                        <label>商品篩選</label>
                                        <select onchange="product_change(this)" id="product_select" name="product_id">
                                            <option value="-1">請選擇商品</option>
                                            @foreach($products as $product)
                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                            @endforeach
                                        </select>
                                        <script>
                                            var product_id = '{{$product_id}}';
                                            console.log("now product_id");
                                            console.log(product_id);
                                            var select = $("#product_select").selectize();
                                            select[0].selectize.setValue(product_id);
                                        </script>
                                        <div id="product_select_div">
                                            <select class="form-control" id="product_detail_select"
                                                    name="product_detail_id">
                                                <option selected value="-1">請選擇商品</option>
                                            </select>
                                        </div>
                                        <button type="submit" class=" btn btn-sm bg-blue" style="width: 100%">篩選
                                        </button>
                                    </div>

                                </form>
                                <div class="col-md-4">
                                    <label>搜尋</label><br>
                                    <!-- search form (Optional) -->
                                    <form action="{{route('senao_products.index')}}" method="get">
                                        <div class="form-inline">
                                            <select name="search_type" class="form-group form-control"
                                                    style="width: 100%;">
                                                <option value="1" @if(request()->get('search_type')==1) selected @endif>
                                                    神腦料號
                                                </option>
                                                <option value="2" @if(request()->get('search_type')==2) selected @endif>
                                                    業務系統ISBN
                                                </option>


                                            </select>
                                            <div class="inline">
                                                <input type="text" name="search_info" class="form-control"
                                                       style="width: 80%"
                                                       placeholder="Search..."
                                                       value="@if(request()->get('search_info')) {{request()->get('search_info')}} @endif">
                                                <button type="submit" id="search-btn" style="cursor: pointer"
                                                        style="width: 20%"
                                                        class="btn btn-flat"><i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{--                                        <input hidden name="code_filter" value="{{$code_filter}}">--}}
                                        {{--                                        <input hidden name="user_filter" value="{{$user_filter}}">--}}
                                        {{--                                        <input hidden name="status_filter" value="{{$status_filter}}">--}}
                                        {{--                                        <input hidden name="date_from" value="{{$date_from}}">--}}
                                        {{--                                        <input hidden name="date_to" value="{{$date_to}}">--}}
                                        {{--                                        <input hidden name="sortBy" value="{{$sortBy}}">--}}
                                    </form>
                                    <!-- /.search form -->


                                </div>

                                <div class="col-md-2">
                                    <label>匯入</label><br>
                                    <div class="inline">
                                        <form action="{{ route('senao_orders.import_product') }}" method="POST"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="file" class="form-control-file">
                                            <button class="btn btn-success btn-sm">匯入神腦商品</button>
                                        </form>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>新增</label><br>
                                    <div class="inline">
                                        <a href="{{route('senao_products.create')}}" class="btn btn-success btn-sm">新增神腦商品</a>
                                    </div>
                                </div>

                            </div>

                        </div>


                        <!-- /.box-header -->
                        <div class="box-body ">


                            <table class="table table-bordered table-hover" width="100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th rowspan="2" class="text-center align-middle"
                                        style="width:10%;vertical-align: middle">神腦料號
                                    </th>
                                    <th colspan="3" class="text-center" style="width:60%;vertical-align: middle">業務系統商品
                                    </th>
                                    <th rowspan="2" class="text-center" style="width:30%;vertical-align: middle">Other
                                    </th>
                                </tr>
                                <tr>
                                    <td class="text-center" style="width: 25%">ISBN</td>
                                    <td class="text-center" style="width: 25%">商品名</td>
                                    <td class="text-center" style="width: 25%">價錢</td>
                                </tr>

                                </thead>


                                @foreach ($senao_products as $senao_product)

                                    @php($rowspan = count($senao_product->isbn_relations) + 1 )

                                    <tr ondblclick="" class="text-center">

                                        <td class="align-middle " style="vertical-align: middle" rowspan="{{$rowspan}}">
                                            {{ $senao_product->senao_isbn}}
                                        </td>
                                    </tr>

                                    <tr>
                                        @foreach($senao_product->isbn_relations as $isbn_relation)

                                            <td class="text-center" style="vertical-align: middle">
                                                {{$isbn_relation->product_relation->ISBN}}
                                            </td>
                                            <td class="text-center" style="vertical-align: middle">
                                                {{$isbn_relation->product_relation->product->name}}&nbsp;{{$isbn_relation->product_relation->product_detail->name}}
                                            </td>
                                            <td class="text-center" style="vertical-align: middle">
                                                ${{$isbn_relation->price}}
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                <script>
                                                    function senao_products_edit(isbn_relation_id) {
                                                        window.location.href = '/senao_products/' + isbn_relation_id + '/edit' + '?source_html=' + encodeURIComponent(window.location.href);
                                                    }
                                                </script>
                                                <a onclick="senao_products_edit({{$isbn_relation->id}})"
                                                   class="btn btn-xs btn-primary">編輯</a>
                                                <br>
                                            </td>

                                            @if(!$loop->last)
                                    </tr>
                                    <tr>@endif

                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>


                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{--                            {{$orders->links()}}--}}
                            {{ $senao_products->appends(request()->input())->links() }}

                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        {{--    </div>--}}
        <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
