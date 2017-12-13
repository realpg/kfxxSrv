@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">数据项管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <a href="{{url('/admin/sjx/edit')}}">
                    <button type="button" class="btn btn-primary">
                        +新建数据项
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
                                <th>数据项</th>
                                <th class="con-th-width-l">描述</th>
                                <th>单位</th>
                                <th>录入时间</th>
                                <th class="opt-th-width-m">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30">{{$data->name}}</span></td>
                                    <td class="con-th-width-l"><span
                                                class="line-height-30 text-oneline">{{$data->desc}}</span>
                                    </td>
                                    <td><span class="line-height-30">{{$data->unit}}</span></td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span></td>
                                    <td class="opt-th-width-m">
                                        <span class="line-height-30">
                                            <a href="{{URL::asset('/admin/sjx/edit')}}?id={{$data->id}}"
                                               class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="编辑该数据项">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </a>
                                            <a href=""
                                               class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                               data-toggle="modal" data-target="#tip_modal"
                                               onclick="">
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
            </div>
        </div>
        {{--模态框--}}
        <div class="modal fade modal-margin-top" id="tip_modal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">提示信息</h4>
                    </div>
                    <div class="modal-body">
                        <div>由于数据项关联了较多信息，因此需要慎重删除，请联系系统管理员进行咨询和处理</div>
                        <div class="margin-top-10">
                            <a href="">数据项的作用，以及如何管理数据项</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('script')
    <script type="application/javascript">

        //提示
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

    </script>
@endsection