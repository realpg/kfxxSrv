@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>企业管理</small>
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
                                    <input id="name" name="name" type="text" class="form-control"
                                           placeholder="根据企业名称搜索"
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
                                <th>名称</th>
                                <th>地址</th>
                                <th>法人</th>
                                <th>电话</th>
                                <th>详情</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->address}}</td>
                                    <td>{{$data->code}}</td>
                                    <td>{{$data->owner}}</td>
                                    <td><a href="{{URL::asset('/admin/enter/info')}}?id={{$data->id}}"
                                           class="a-pointer margin-right-10" onclick="showTip();">详情&nbsp&nbsp</a>
                                        <a href="{{URL::asset('/admin/enter/del')}}/{{$data->id}}" class="a-pointer"
                                           onclick="showTip();">删除</a></td>
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