@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">医师/康复师管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <a href="{{url('/admin/doctor/edit')}}">
                    <button type="button" class="btn btn-primary">
                        +新建医师/康复师/管理员
                    </button>
                </a>
            </div>
        </div>
    </section>


    <!-- Main content -->
    <section class="content">
        <!--列表-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>头像</th>
                                <th>姓名</th>
                                <th>部门</th>
                                <th>职务</th>
                                <th>角色</th>
                                <th>手机</th>
                                <th class="opt-th-width-m">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td>
                                        <img src="{{ $data->avatar ? $data->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             class="img-rect-30 radius-5">
                                    </td>
                                    <td><span class="line-height-30">
                                            <a href="{{URL::asset('/admin/doctor/edit')}}?id={{$data->id}}">
                                            {{$data->name}}
                                            </a>
                                        </span></td>
                                    <td><span class="line-height-30">{{$data->dep}}</span></td>
                                    <td><span class="line-height-30">{{$data->duty}}</span></td>
                                    <td>
                                        <span class="line-height-30">
                                        @if($data->role==='0')
                                                <span class="label label-info line-height-30">医师</span>
                                            @endif
                                            @if($data->role==='1')
                                                <span class="label label-success line-height-30">康复师</span>
                                            @endif
                                            @if($data->role==='2')
                                                <span class="label label-warning line-height-30">管理员</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                         <span class="line-height-30">
                                        {{$data->phonenum}}
                                         </span>
                                    </td>
                                    <td class="opt-th-width-m">
                                        <span class="line-height-30">
                                            <a href="{{URL::asset('/admin/doctor/edit')}}?id={{$data->id}}"
                                               class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="编辑该角色">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/doctor/del')}}/{{$data->id}}"
                                               class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="删除该角色">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </a>
                                        </span>
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
        <div class="row">
            <div class="col-sm-5">

            </div>
            <div class="col-sm-7 text-right">
                {!! $datas->links() !!}
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="application/javascript">

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection