@extends('layouts.master')

@section('title', '客戶資訊詳細')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                客戶資訊詳細
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('customers.index')}}"><i class="fa fa-shopping-bag"></i> 客戶管理</a></li>
                <li class="active">客戶資訊詳細</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="container">

                <form class="well form-horizontal" action=" " method="post" id="contact_form">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-4 control-label">客戶名稱</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="text" class="form-control" id="title" name="name" placeholder="請輸入名稱"
                                           value="{{ old('name', $customer->name) }}" disabled="disabled">
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label">負責業務</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <select id="user_id" name="user_id" class="form-control selectpicker"
                                            disabled="disabled">
                                        {{--                                    @if($customer->already_set_sales==0)--}}
                                        {{--                                        <option>無</option>--}}
                                        {{--                                    @else--}}
                                        @foreach($users as $user)
                                            @if($customer->already_set_sales==0)
                                                <option value="0">無</option>
                                                @break
                                            @endif
                                            <option
                                                value="{{ $user->id }}"{{ (old('$user_id',$user->id) == $customer->user_id)? ' selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                        {{--                                    @endif--}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">統編</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                                    <input type="text" class="form-control" id="tax_id" name="tax_id"
                                           disabled="disabled"
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
                                           disabled="disabled"
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
                                           disabled="disabled"
                                           value="{{ old('scales', $customer->scales) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">縣市</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="請輸入縣市"
                                           disabled="disabled"
                                           value="{{ old('city', $customer->city) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">地區</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                    <input type="text" class="form-control" id="area" name="area" placeholder="請輸入地區"
                                           disabled="disabled"
                                           value="{{ old('area', $customer->area) }}">
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
                                           disabled="disabled"
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
                                           placeholder="請輸入電話" disabled="disabled"
                                           value="{{ old('phone_number', $customer->phone_number) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">傳真</label>
                            <div class="col-md-4 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                                    <input type="text" class="form-control" id="fax_number" name="fax_number"
                                           placeholder="請輸入傳真" disabled="disabled"
                                           value="{{ old('fax_number', $customer->fax_number) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">狀態</label>
                            <div class="col-md-4 selectContainer">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-list"></i></span>
                                    <select id="status" name="status" class="form-control selectpicker"
                                            disabled="disabled">
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
                                    <select id="active_status" name="active_status" class="form-control selectpicker"
                                            disabled="disabled">
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
                                <a class="btn btn-primary" href="{{route('customers.index')}}">客戶列表</a>
                                <a class="btn btn-primary" href="{{route('customers.edit',$customer->id)}}">編輯</a>
                                @if($customer->user_id==Auth::user()->id or Auth::user()->level==2)

                                    <form action="{{ route('customers.delete', $customer->id) }}" method="post"
                                          style="display: inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('確定是否刪除')">刪除
                                        </button>
                                    </form>
                                @endif
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
