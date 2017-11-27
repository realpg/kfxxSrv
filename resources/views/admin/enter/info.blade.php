@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>企业信息详情</small>
        </h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        企业名称：{{$datas->name}}
                        <span style="margin-left: 25px;">id：{{$datas->id}}</span>
                    </div>
                    <div class="box-body">
                        <ul class="list-group">
                            <li class="list-group-item">地址：{{$datas->address}}</li>
                            <li class="list-group-item">邮编：{{$datas->code}}</li>
                            <li class="list-group-item">营业执照：
                                <img src="{{$datas->lice_img.'?imageView2/1/w/400/interlace/1/q/75|imageslim'}}"
                                     style="width: 300px;">
                            </li>
                            <li class="list-group-item">税号：{{$datas->tax_code}}</li>
                            <li class="list-group-item">税务登记：
                                <img src="{{$datas->tax_img.'?imageView2/1/w/400/interlace/1/q/75|imageslim'}}"
                                     style="width: 300px;">
                            </li>
                            <li class="list-group-item">法人姓名：{{$datas->owner}}</li>
                            <li class="list-group-item">法人身份证：{{$datas->owner_no}}</li>
                            <li class="list-group-item">法人电话：{{$datas->owner_tel}}</li>
                            <li class="list-group-item">证件照片：
                                <img src="{{$datas->owner_card1.'?imageView2/1/w/400/interlace/1/q/75|imageslim'}}"
                                     style="width: 300px;">
                            </li>
                            <li class="list-group-item">证件照片：
                                <img src="{{$datas->owner_card2.'?imageView2/1/w/400/interlace/1/q/75|imageslim'}}"
                                     style="width: 300px;">
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                    <!-- /.box-footer-->
                </div>
            </div>
            <!--/.col (right) -->
            <div class="col-md-6">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        录入人：{{$user->nick_name}}
                        <span style="margin-left: 25px;">id：{{$user->id}}</span>
                    </div>
                    <div class="box-body">
                        <ul class="list-group">
                            <li class="list-group-item">头像：
                                <img src="{{ $user->avatar ? $user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/logo.png')}}"
                                     class="head-icon-sma">
                            </li>
                            <li class="list-group-item">电话：{{$user->phonenum}}</li>
                            <li class="list-group-item">性别：{{$user->gender === '1'?'男':'女'}}</li>
                            <li class="list-group-item">省份：{{$user->province}}</li>
                            <li class="list-group-item">城市：{{$user->city}}</li>
                            <li class="list-group-item">token：{{$user->token}}</li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                    <!-- /.box-footer-->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ URL::asset('js/qiniu.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/plupload.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/moxie.js') }}"></script>
    <script type="application/javascript">

    </script>
@endsection