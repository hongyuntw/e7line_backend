@extends('layouts.master')

@section('title', '編輯客戶資訊')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                編輯客戶資訊
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('customers.index')}}"><i class="fa fa-shopping-bag"></i> 客戶管理</a></li>
                <li class="active">編輯客戶資訊</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">

                <form class="well form-horizontal" action="{{ route('customers.update', $customer->id) }}" method="post" id="contact_form">

                    @csrf
                    @method('PATCH')

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                &times;
                            </button>
                            <h4><i class="icon fa fa-ban"></i> 錯誤！</h4>
                            請修正以下表單錯誤：
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-4 control-label">客戶名稱</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="title" name="name" placeholder="請輸入名稱"
                                           value="{{ old('name', $customer->name) }}" >
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label">負責業務</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
{{--                                    <select id="user_id" name="user_id" class="form-control selectpicker"--}}
{{--                                            >--}}
{{--                                        --}}{{--                                    @if($customer->already_set_sales==0)--}}
{{--                                        --}}{{--                                        <option>無</option>--}}
{{--                                        --}}{{--                                    @else--}}
{{--                                        @foreach($users as $user)--}}
{{--                                            @if($customer->already_set_sales==0)--}}
{{--                                                <option value="0">無</option>--}}
{{--                                                @break--}}
{{--                                            @endif--}}
{{--                                            <option--}}
{{--                                                value="{{ $user->id }}"{{ (old('$user_id',$user->id) == $customer->user_id)? ' selected' : '' }}>--}}
{{--                                                {{ $user->name }}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                        --}}{{--                                    @endif--}}
{{--                                    </select>--}}
                                    @if(Auth::user()->level==2)
{{--                                        <div class="form-group">--}}
                                            <select id="user_id" name="user_id" class="form-control">
                                                @foreach($users as $user)
                                                    <option
                                                        value="{{ $user->id }}"{{ (old('user_id',$customer->user_id) == $user->id)? ' selected' : '' }}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
{{--                                        </div>--}}
                                    @else
{{--                                        <div class="form-group">--}}
                                            @if($customer->already_set_sales == 1)
                                                <select id="user_id" name="user_id" class="form-control"
                                                        disabled="disabled">
                                                    @foreach($users as $user)
                                                        <option
                                                            value="{{ $user->id }}"{{ ($user->id == $customer->user_id)? ' selected' : '' }}>{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select id="user_id" name="user_id" class="form-control">
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
{{--                                        </div>--}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">統編</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="text" class="form-control" id="tax_id" name="tax_id"

                                           placeholder="請輸入統編" value="{{ old('tax_id', $customer->tax_id) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">資本額</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input type="number" class="form-control" id="capital" name="capital"
                                           placeholder="請輸入資本額" value="{{ old('capital', $customer->capital) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">規模</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="number" class="form-control" id="scales" name="scales"
                                           placeholder="請輸入規模"
                                           value="{{ old('scales', $customer->scales) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">縣市及地區</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <div id="twzipcode"></div>
                                    <script>
                                        $("#twzipcode").twzipcode({
                                            countySel: '{!! $customer->city!!}', // 城市預設值, 字串一定要用繁體的 "臺", 否則抓不到資料
                                            districtSel: '{!! $customer->area!!}', // 地區預設值
                                            zipcodeIntoDistrict: true, // 郵遞區號自動顯示在地區
                                            css: ["city form-control", "town form-control"], // 自訂 "城市"、"地區" class 名稱
                                            countyName: "city", // 自訂城市 select 標籤的 name 值
                                            districtName: "area" // 自訂地區 select 標籤的 name 值
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">地址</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <input type="text" class="form-control" id="address" name="address"
                                           placeholder="請輸入地址"
                                           value="{{ old('address', $customer->address) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">電話</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                                           placeholder="請輸入電話" value="{{ old('phone_number', $customer->phone_number) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">傳真</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                                    <input type="text" class="form-control" id="fax_number" name="fax_number"
                                           placeholder="請輸入傳真"
                                           value="{{ old('fax_number', $customer->fax_number) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">狀態</label>
                            <div class="col-md-4 selectContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                    <select id="status" name="status" class="form-control selectpicker">
                                        @foreach([1,2,3,4,5] as $st_id)
                                            <option
                                                value="{{ $st_id }}"{{ (old('$st_id', $customer->status) == $st_id)? ' selected' : '' }}>{{ $status_text[$st_id] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">是否開通</label>
                            <div class="col-md-4 selectContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>

                                    <select id="active_status" name="active_status" class="form-control">
                                        <option
                                            value="0" {{ old('active_status',$customer->active_status) == 0 ? 'selected' : '' }}>
                                            否
                                        </option>
                                        <option
                                            value="1" {{ old('active_status',$customer->active_status) == 1 ? 'selected' : '' }}>
                                            是
                                        </option>

                                    </select>
                                </div>
                            </div>
                        </div>


                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-4">
                                <a class="btn btn-danger" href="{{ URL::previous() }}">取消</a>
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>
                        </div>

                    </fieldset>
                </form>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
