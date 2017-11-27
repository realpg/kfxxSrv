@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>管理员</small>
        </h1>
        <ol class="breadcrumb">
            <a href="{{url('/admin/admin/edit')}}">
                <button type="submit" class="btn btn-info"
                        style="height: 24px;padding-top: 0px;padding-bottom: 0px;font-size: 12px;">
                    新建管理员+
                </button>
            </a>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="tip_div" class="alert alert-info hidden" role="alert">请在执行相关请求，完成后页面将自动刷新</div>
        <!--列表-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>头像</th>
                                <th>昵称</th>
                                <th>手机号</th>
                                <th>级别</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>
                                        <img src="{{ $admin->avatar ? $admin->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/logo.png')}}"
                                             class="head-icon-sma">
                                    </td>
                                    <td>{{$data->nick_name}}</td>
                                    <td>{{$data->phonenum}}</td>
                                    <td>
                                        {{$data->role==='0'?'根级管理员':'管理员'}}
                                    </td>
                                    <td>
                                        <a href="{{URL::asset('/admin/admin/edit')}}?id={{$data->id}}"
                                           class="a-pointer margin-right-10" onclick="showTip();">编辑&nbsp&nbsp</a>
                                        <a href="{{URL::asset('/admin/admin/del')}}/{{$data->id}}" class="a-pointer"
                                           onclick="showTip();">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('script')
    <script type="application/javascript">

        function showTip() {
            $("#tip_div").removeClass('hidden');
        }
    </script>
@endsection