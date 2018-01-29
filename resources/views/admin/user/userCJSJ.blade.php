@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">患者管理</li>
                    <li class="active">采集数据管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>采集时间</th>
                            <th>数据项名称</th>
                            <th>位置</th>
                            <th>患侧</th>
                            <th>测量值</th>
                            <th>详细位置</th>
                            <th>结果</th>
                            <th class="opt-th-width">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <td>
                                    <div class="weui-cells__title">{{$data->created_at}}</div>
                                </td>
                                <td>
                                    <div class="weui-cell__bd">{{$data->sjx->name}}</div>
                                </td>
                                <td>
                                    <div class="weui-cell__ft">{{$data->hpos->name}}</div>
                                </td>
                                <td>
                                    @if($data->c_side=='n')
                                        <div class="weui-cell__ft">不区分</div>
                                    @elseif($data->c_side=='l')
                                        <div class="weui-cell__ft">左侧</div>
                                    @elseif($data->c_side=='r')
                                        <div class="weui-cell__ft">右侧</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="weui-cell__ft">{{$data->value}}</div>
                                </td>
                                <td>
                                    @if($data->sjx->is_dis_pos==1)
                                        <div class="weui-cell__ft">{{$data->c_pos}}</div>
                                    @else
                                        <div>不区分</div>
                                    @endif
                                </td>
                                <td>
                                    @if($data->result)
                                        <div class="weui-cell__ft">未处理</div>
                                    @elseif($data->result==0)
                                        <div class="weui-cell__ft" style="color:green">正常</div>
                                    @elseif($data->result==1)
                                        <div class="weui-cell__ft" style="color:red">异常</div>
                                    @endif
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
@endsection


@section('script')
    <script type="application/javascript">


    </script>
@endsection