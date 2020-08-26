<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('img/123.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{Auth::user()->name}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

    {{--        <!-- search form (Optional) -->--}}
    {{--        <form role="form" action="{{route('products.search')}}" method="get" class="sidebar-form">--}}
    {{--            <div class="input-group">--}}
    {{--                <input type="text" name="namebesearch" class="form-control" placeholder="Search Product...">--}}
    {{--                <span class="input-group-btn">--}}
    {{--              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>--}}
    {{--              </button>--}}
    {{--            </span>--}}
    {{--            </div>--}}
    {{--        </form>--}}
    {{--        <!-- /.search form -->--}}

    <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">管理系統</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="{{ (request()->is('/'))? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}">
                    <i class="fa fa-dashboard"></i> <span>首頁</span>
                </a>
            </li>


            <li class="treeview{{ (request()->is('products*'))? ' active' : '' }}">
                <a href="#">
                    <i class="fa fa-address-book"></i>
                    <span>業務資料</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('users.index')}}">業務列表</a></li>
                    <li><a href="{{route('users.create')}}">新增業務</a></li>

                </ul>
                <a href="#">
                    <i class="fa fa-address-book"></i>
                    <span>客戶資料</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('customers.index') }}">客戶列表</a></li>
                    <li><a href="{{route('customers.create')}}">新增客戶</a></li>
                </ul>
                <a href="#">
                    <i class="fa fa-shopping-bag"></i>
                    <span>福利狀況</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('welfare_status.index')}}">福利列表</a></li>
                    <li><a href="{{route('welfare_status.add_welfare_type')}}">新增福利類別</a></li>
                    <li><a href="{{route('welfare_status.add_customer_welfare')}}">新增客戶福利</a></li>
                </ul>

                <a href="#">
                    <i class="fa fa-shopping-bag"></i>
                    <span>交易狀況</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('orders.index')}}">訂單列表</a></li>
                    <li><a href="{{route('senao_orders.index')}}">神腦訂單列表</a></li>
                    <li><a href="{{route('order_items.index')}}">訂單細項列表</a></li>
                    <li><a href="{{route('orders.create')}}">新增訂單</a></li>
                    <li><a href="{{route('products.create')}}">新增商品</a></li>
                    <li><a href="{{route('products.edit')}}">編輯商品</a></li>

                </ul>
                <a href="#">
                    <i class="fa fa-shopping-bag"></i>
                    <span>郵件列表</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('mails.index')}}">郵件列表</a></li>
                </ul>
                <a href="#">
                    @php
                        $user = Auth::user();
                        $task_unprocess = \App\TaskAssignment::where('status','=',0)->where('user_id','=',$user->id)->get();
                        $count = count($task_unprocess);
                    @endphp
                    <i class="fa fa-shopping-bag"></i>
                    <span>任務清單</span>
                    @if($count>0 && $user->level!=2)
                        <div
                            style="
                        height: 20px;
                        width: 20px;
                        background-color: red; color: white;

                        border-radius: 50%;
                        display: inline-block;
                        text-align: center;">
                            @if($count>=99)
                                N
                            @else
                                {{$count}}
                            @endif
                        </div>
                    @elseif($user->level == 2)
                        @php
                            $be_checked_count = count(\App\TaskAssignment::where('status','=',1)->get());

                        @endphp
                        @if($be_checked_count > 0 )
                            <div
                                style="
                        height: 20px;
                        width: 20px;
                        background-color: red; color: white;

                        border-radius: 50%;
                        display: inline-block;
                        text-align: center;">
                                @if($be_checked_count>=99)
                                    N
                                @else
                                    {{$be_checked_count}}
                                @endif
                            </div>
                        @endif

                    @endif
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>


                <ul class="treeview-menu">
                    <li><a href="{{route('tasks.index')}}">任務列表</a></li>
                    @if(Auth::user()->level==2)
                        <li><a href="{{route('tasks.create')}}">新增任務</a></li>
                    @endif
                </ul>
                <a href="#">
                    <i class="fa fa-shopping-bag"></i>
                    <span>報價參考</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('quote.index')}}">報價參考</a></li>
                    <li><a href="{{route('quote.create')}}">新增報價參考</a></li>
                </ul>
                <a href="{{route('report.index')}}">
                    <i class="fa fa-shopping-bag"></i>
                    <span>業務報表</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('report.index')}}">報表</a></li>
                    <li><a href="{{route('record.index')}}">開發紀錄</a></li>
                </ul>
            </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
