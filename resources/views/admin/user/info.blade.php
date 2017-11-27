@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>用户信息详情</small>
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
                        姓名：{{$datas->nick_name}}
                        <span style="margin-left: 25px;">id：{{$datas->id}}</span>
                    </div>
                    <div class="box-body">
                        <ul class="list-group">
                            <li class="list-group-item">头像：
                                <img src="{{ $datas->avatar ? $datas->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/logo.png')}}"
                                     class="head-icon-sma">
                            </li>
                            <li class="list-group-item">电话：{{$datas->phonenum}}</li>
                            <li class="list-group-item">性别：{{$datas->gender === '1'?'男':'女'}}</li>
                            <li class="list-group-item">省份：{{$datas->province}}</li>
                            <li class="list-group-item">城市：{{$datas->city}}</li>
                            <li class="list-group-item">token：{{$datas->token}}</li>
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
                @foreach($enters as $enter)
                    <div class="box box-info">
                        <div class="box-header with-border">
                            录入企业：{{$enter->name}}
                            <span style="margin-left: 25px;">id：{{$enter->id}}</span>
                        </div>
                        <div class="box-body">
                            <ul class="list-group">
                                <li class="list-group-item">地址：{{$enter->address}}</li>
                                <li class="list-group-item">邮编：{{$enter->code}}</li>
                                <li class="list-group-item">营业执照：
                                    <img src="{{$enter->lice_img.'?imageView2/1/w/400/interlace/1/q/75|imageslim'}}"
                                         style="width: 300px;">
                                </li>
                                <li class="list-group-item">税号：{{$enter->tax_code}}</li>
                                <li class="list-group-item">税务登记：
                                    <img src="{{$enter->tax_img.'?imageView2/1/w/400/interlace/1/q/75|imageslim'}}"
                                         style="width: 300px;">
                                </li>
                                <li class="list-group-item">法人姓名：{{$enter->owner}}</li>
                                <li class="list-group-item">法人身份证：{{$enter->owner_no}}</li>
                                <li class="list-group-item">法人电话：{{$enter->owner_tel}}</li>
                                <li class="list-group-item">证件照片：
                                    <img src="{{$enter->owner_card1.'?imageView2/1/w/400/interlace/1/q/75|imageslim'}}"
                                         style="width: 300px;">
                                </li>
                                <li class="list-group-item">证件照片：
                                    <img src="{{$enter->owner_card2.'?imageView2/1/w/400/interlace/1/q/75|imageslim'}}"
                                         style="width: 300px;">
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">

                        </div>
                        <!-- /.box-footer-->
                    </div>
                @endforeach
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