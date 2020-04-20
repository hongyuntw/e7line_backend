@extends('layouts.master')

@section('title', '客戶列表')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <a href="{{route('customers.index')}}">客戶管理</a>
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-shopping-bag"></i> 客戶管理</a></li>
                <li class="active">客戶列表</li>
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
                            <div class="row">
                                <form name="filter_form" action="{{route('customers.index')}}" method="get">
                                    <div class="col-md-2 col-3">
                                        <label>客戶篩選</label>
                                        <select name="user_filter" class="form-control form-control-sm"
                                                value="{{$user_filter}}">
                                            <option value="0" @if($user_filter==0) selected @endif>全部客戶</option>
                                            <option value="1" @if($user_filter==1) selected @endif>我的客戶</option>
                                            <option value="2" @if($user_filter==2) selected @endif>未指定業務</option>

                                        </select>
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <label>狀態篩選</label>
                                        <select multiple name="status_filter[]"
                                                class="form-control form-control-sm">
                                            @foreach(['0','1','2','3','4','5'] as $col)
                                                <option
                                                    @if(is_array($status_filter))
                                                    @if(in_array($col, $status_filter))
                                                    selected
                                                    @endif
                                                    @endif
                                                    value="{{$col}}">{{$status_text[$loop->index]}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <label>排序方式</label>
                                        <select multiple name="sortBy[]" class="form-control form-control-sm">
                                            @foreach(['create_date','city','area','user_id','status'] as $col)
                                                <option
                                                    @if(is_array($sortBy))
                                                    @if(in_array($col, $sortBy))
                                                    selected
                                                    @endif
                                                    @endif
                                                    value="{{$col}}">{{$sortBy_text[$loop->index]}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-3">
                                        <label>篩選按鈕</label><br>
                                        <button type="submit" class="w-100 btn btn-sm bg-blue">Filter</button>
                                    </div>
                                </form>
                                <div class="col-md-3 col-3">
                                    <label>搜尋</label><br>
                                    <!-- search form (Optional) -->
                                    <form action="{{route('customers.index')}}" method="get">
                                        <div class="form-inline">
                                            <select name="search_type" class="form-group form-control">
                                                <option value="1" @if(request()->get('search_type')==1) selected @endif>
                                                    客戶名稱
                                                </option>
                                                <option value="2" @if(request()->get('search_type')==2) selected @endif>
                                                    地區
                                                </option>

                                            </select>
                                            <br>
                                            <div class="inline">
                                                <input type="text" name="search_info" class="form-control"
                                                       placeholder="Search..."
                                                       value="@if(request()->get('search_info')) {{request()->get('search_info')}} @endif">
                                                <button type="submit" id="search-btn" style="cursor: pointer"
                                                        class="btn btn-flat"><i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <select multiple name="status_filter[]" hidden>
                                            @if(request()->get('status_filter'))
                                                @foreach(request()->get('status_filter') as $col)
                                                    <option selected value="{{$col}}"></option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <select multiple name="sortBy[]" hidden>
                                            @if(request()->get('sortBy'))
                                                @foreach(request()->get('sortBy') as $col)
                                                    <option selected value="{{$col}}"></option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </form>
                                    <!-- /.search form -->

                                </div>
                                <div class="col-md-1 col-3">
                                    <label>特殊功能</label><br>
                                    <a class="btn btn-success btn-sm" href="{{route('customers.create')}}">新增客戶</a>
                                </div>


                            </div>


                        </div>

                        <!-- /.box-header -->
                        <div class="box-body ">

                            <table class="table table-bordered table-hover" width="100%">
                                <thead style="background-color: lightgray">
                                <tr>
                                    <th class="text-center" style="width:25%">客戶名稱</th>
                                    <th class="text-center" style="width:10%">電話</th>
                                    <th class="text-center" style="width:10%">規模</th>
                                    <th class="text-center" style="width:10%">縣市地區</th>
                                    <th class="text-center" style="width:10%">狀態
                                        <a href="#" id="question_mark_icon">
                                            <span class="glyphicon glyphicon-question-sign"></span>
                                            <img id="customer_status_img" src="/img/customer_status_description.png"
                                                 style="position: absolute">
                                        </a>
                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                var $img = $("#customer_status_img");
                                                $img.hide();
                                                $("#question_mark_icon").mousemove(function (e) {
                                                    $img.stop(1, 1).fadeIn();
                                                    $img.offset({
                                                        top: e.pageY - $img.outerHeight(),
                                                        left: e.pageX - ($img.outerWidth() / 2)
                                                    });
                                                }).mouseleave(function () {
                                                    $img.fadeOut();
                                                });
                                            });
                                        </script>

                                    </th>
                                    <th class="text-center" style="width: 6%">業務</th>
                                    <th class="text-center" style="width: 8%">窗口/開發數</th>

                                    <th class="text-center" style="width: 15%">其他功能</th>

                                </tr>
                                </thead>

                                <script>
                                    function dbclick_on_customer(customer_id) {
                                        window.location.href = '/customers/' + customer_id + '/record';
                                    }
                                </script>
                                @foreach ($customers as $customer)
                                    @if($customer->is_deleted==1)
                                        @continue
                                    @endif

                                    <tr ondblclick="dbclick_on_customer({{$customer->id}})" class="text-center">
                                        <td class="text-left">
                                            <svg class="bi bi-briefcase-fill" width="1em" height="1em"
                                                 viewBox="0 0 16 16" fill="currentColor"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                      d="M0 12.5A1.5 1.5 0 001.5 14h13a1.5 1.5 0 001.5-1.5V6.85L8.129 8.947a.5.5 0 01-.258 0L0 6.85v5.65z"
                                                      clip-rule="evenodd"/>
                                                <path fill-rule="evenodd"
                                                      d="M0 4.5A1.5 1.5 0 011.5 3h13A1.5 1.5 0 0116 4.5v1.384l-7.614 2.03a1.5 1.5 0 01-.772 0L0 5.884V4.5zm5-2A1.5 1.5 0 016.5 1h3A1.5 1.5 0 0111 2.5V3h-1v-.5a.5.5 0 00-.5-.5h-3a.5.5 0 00-.5.5V3H5v-.5z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                            {{ $customer->name }}</td>
                                        <td>{{ $customer->phone_number  }}</td>
                                        <td>{{ $customer->scales  }}人</td>
                                        <td class="text-left">{{ $customer->city }}{{$customer->area}} </td>
                                        @if ($customer->status==1)
                                            @php($css='label label-danger')
                                        @elseif($customer->status==2)
                                            @php($css='label label-success')
                                        @elseif($customer->status==3)
                                            @php($css='label label-info')
                                        @elseif($customer->status==4)
                                            @php($css='label label-primary')
                                        @elseif($customer->status==5)
                                            @php($css='label label-warning')
                                        @endif
                                        <td class="align-middle " style="vertical-align: middle"><label
                                                class="label{{$css}}"
                                                style="min-width:60px;display: inline-block">{{ $status_text[$customer->status] }}</label>
                                        </td>
                                        <td class="text-left">{{ $customer->user->name }} </td>
                                        <td>
                                            {{count($customer->business_concat_persons)}}
                                            /
                                            {{count($customer->concat_records)}}

                                        </td>
                                        <td class="text-center">
                                            @if(request()->get('page'))
                                                @php($page=request()->get('page'))
                                            @else
                                                @php($page=1)
                                            @endif
                                            <a href="{{ route('customers.show', $customer->id) }}"
                                               class="btn btn-xs btn-primary">詳細</a>
                                            <a href="{{ route('customers.edit', $customer->id,['page'=>$page])}}"
                                               class="btn btn-xs btn-primary">編輯</a>

                                            <a href="{{ route('customers.record', $customer->id) }}"
                                               class="btn btn-xs btn-primary">紀錄</a>
                                            @if($customer->user_id==Auth::user()->id or Auth::user()->level==2)
                                                <form action="{{ route('customers.delete', $customer->id) }}"
                                                      method="post"
                                                      style="display: inline-block">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-danger"
                                                            onclick="return confirm('確定是否刪除')">刪除
                                                    </button>
                                                </form>
                                            @endif
                                        </td>


                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{ $customers->appends(request()->input())->links() }}
                        </div>
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
