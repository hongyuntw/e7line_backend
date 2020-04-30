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
                    <li><a href="{{route('order_items.index')}}">訂單細項列表</a></li>
                    <li><a href="{{route('orders.create')}}">新增訂單</a></li>
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
                    <i class="fa fa-shopping-bag"></i>
                    <span>報價參考</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="">報價列表</a></li>
                    <li><a href="">報價--</a></li>
                </ul>
{{--                <a href="#">--}}
{{--                    <i class="fa fa-shopping-bag"></i>--}}
{{--                    <span>商品管理</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--                        <i class="fa fa-angle-left pull-right"></i>--}}
{{--                    </span>--}}
{{--                </a>--}}
{{--                <ul class="treeview-menu">--}}
{{--                    <li><a href="{{ route('products.index') }}">商品列表</a></li>--}}
{{--                    <li><a href="{{ route('products.create') }}">新增商品</a></li>--}}
{{--                </ul>--}}
{{--                <a href="#">--}}
{{--                    <i class="fa fa-shopping-bag"></i>--}}
{{--                    <span>訂單管理</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--                        <i class="fa fa-angle-left pull-right"></i>--}}
{{--                    </span>--}}
{{--                </a>--}}
{{--                <ul class="treeview-menu">--}}
{{--                    <li><a href="{{ route('sales.index') }}">訂單列表</a></li>--}}
{{--                </ul>--}}
{{--                <a href="#">--}}
{{--                    <i class="fa fa-shopping-bag"></i>--}}
{{--                    <span>會員管理</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--                        <i class="fa fa-angle-left pull-right"></i>--}}
{{--                    </span>--}}
{{--                </a>--}}
{{--                <ul class="treeview-menu">--}}
{{--                    <li><a href="{{ route('members.index') }}">會員列表</a></li>--}}
{{--                </ul>--}}
{{--                <a href="#">--}}
{{--                    <i class="fa fa-shopping-bag"></i>--}}
{{--                    <span>廣告管理</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--                        <i class="fa fa-angle-left pull-right"></i>--}}
{{--                    </span>--}}
{{--                </a>--}}
{{--                <ul class="treeview-menu">--}}
{{--                    <li><a href="{{ route('ads.index') }}">廣告列表</a></li>--}}
{{--                </ul>--}}
{{--                <a href="#">--}}
{{--                    <i class="fa fa-shopping-bag"></i>--}}
{{--                    <span>客服服務</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--                        <i class="fa fa-angle-left pull-right"></i>--}}
{{--                    </span>--}}
{{--                </a>--}}
{{--                <ul class="treeview-menu">--}}
{{--                    <li><a href="{{ route('suggestions.index') }}">意見管理</a></li>--}}
{{--                </ul>--}}
{{--                <a href="#">--}}
{{--                    <i class="fa fa-shopping-bag"></i>--}}
{{--                    <span>優惠券服務</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--                        <i class="fa fa-angle-left pull-right"></i>--}}
{{--                    </span>--}}
{{--                </a>--}}
{{--                <ul class="treeview-menu">--}}
{{--                    <li><a href="{{ route('coupons.index') }}">優惠券列表</a></li>--}}
{{--                </ul>--}}
            </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
