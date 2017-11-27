@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>资讯管理（政策、咨询、简介）</small>
        </h1>
        <ol class="breadcrumb">
            <a href="{{url('/admin/zx/edit')}}">
                <button type="submit" class="btn btn-info"
                        style="height: 24px;padding-top: 0px;padding-bottom: 0px;font-size: 12px;">
                    新建资讯+
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
                                <th>标题</th>
                                <th>创建时间</th>
                                <th>排序</th>
                                <th>类型</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>{{$data->title}}
                                    </td>
                                    <td>{{$data->created_at}}</td>
                                    <td>{{$data->seq}}</td>
                                    <td>
                                        {{$data->type=='0'?'资讯':''}}
                                        {{$data->type=='1'?'政策':''}}
                                        {{$data->type=='2'?'简介':''}}
                                    </td>
                                    <td>
                                        {{$data->status==='0'?'失效':'生效'}}
                                    </td>
                                    <td>
                                        <a href="{{URL::asset('/admin/zx/setStatus')}}/{{$data->id}}?opt=1"
                                           class="a-pointer" onclick="showTip();">生效&nbsp&nbsp</a>
                                        <a href="{{URL::asset('/admin/zx/setStatus')}}/{{$data->id}}?opt=0"
                                           class="a-pointer margin-right-10" onclick="showTip();">失效&nbsp&nbsp</a>
                                        <a href="{{URL::asset('/admin/tw/edit')}}?f_id={{$data->id}}&f_type=0"
                                           class="a-pointer margin-right-10" onclick="showTip();">设置图文&nbsp&nbsp</a>
                                        <a href="{{URL::asset('/admin/zx/edit')}}?id={{$data->id}}"
                                           class="a-pointer margin-right-10" onclick="showTip();">编辑&nbsp&nbsp</a>
                                        <a href="{{URL::asset('/admin/zx/del')}}/{{$data->id}}" class="a-pointer"
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