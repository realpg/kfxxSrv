@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">量表管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <a href="{{url('/admin/lb/edit')}}">
                    <button type="button" class="btn btn-primary">
                        +新建量表
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
                            <tr class="opt-th-width">
                                <th>标题</th>
                                <th>创建时间</th>

                                <th>状态</th>
                                <th class="opt-th-width-l">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30">
                                        <a href="{{URL::asset('/admin/lb/edit')}}?id={{$data->id}}">
                                            {{$data->name}}
                                        </a>
                                        </span></td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span>
                                    </td>

                                    <td>
                                        @if($data->status === '0')
                                            <span class="label label-default line-height-30">失效</span>
                                        @else
                                            <span class="label label-success line-height-30">生效</span>
                                        @endif
                                    </td>
                                    <td class="opt-th-width-l">
                                        <span class="line-height-30">
                                            <a href="{{URL::asset('/admin/lb/setStatus')}}/{{$data->id}}?opt=1"
                                               class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="生效的量表才可以推送给客户">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/lb/setStatus')}}/{{$data->id}}?opt=0"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="失效的量表图文不会推送给客户">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/lb/edit')}}?id={{$data->id}}"
                                               class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="编辑该量表">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </a>
                                            <a href=""
                                               class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                               data-toggle="modal" data-target="#tip_modal"
                                               onclick=""
                                               title="删除该量表图文">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </a>
                                             <a href="{{URL::asset('/admin/lb/setStep')}}/{{$data->id}}"
                                                class="btn btn-social-icon btn-primary margin-right-10 opt-btn-size"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="设置量表图文">
                                                <i class="fa fa-image opt-btn-i-size"></i>
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
                        <div>由于量表关联了较多信息，因此需要慎重删除，请联系系统管理员进行咨询和处理</div>
                        <div class="margin-top-10">
                            <a href=""></a>
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

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })


    </script>
@endsection