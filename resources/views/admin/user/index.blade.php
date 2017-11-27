@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>用户管理</small>
        </h1>
        <ol class="breadcrumb">
            <a href="#">
            </a>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="tip_div" class="alert alert-info hidden" role="alert">请在执行相关请求，完成后页面将自动刷新</div>

        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box">
                    <!-- form start -->
                    <form action="" method="post" class="form-horizontal">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input id="nick_name" name="nick_name" type="text" class="form-control"
                                           placeholder="根据用户名称搜索"
                                           value="">
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-info btn-block btn-flat" onclick="">
                                        搜索
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>

        <!--列表-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>头像</th>
                                <th>昵称</th>
                                <th>注册时间</th>
                                <th>最后登录时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>
                                        <img src="{{ $admin->avatar ? $admin->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/logo.png')}}"
                                             class="head-icon-sma"></td>
                                    <td>{{$data->nick_name}}</td>
                                    <td>{{$data->created_at}}</td>
                                    <td>{{$data->updated_at}}</td>
                                    <td><a href="{{URL::asset('/admin/user/info')}}?id={{$data->id}}"
                                           class="a-pointer margin-right-10" onclick="showTip();">详情&nbsp&nbsp</a></td>
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