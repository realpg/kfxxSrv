@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">宣教管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <a href="{{url('/admin/xj/edit')}}">
                    <button type="button" class="btn btn-primary">
                        +新建宣教
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
                                <th>作者</th>
                                <th>状态</th>
                                <th class="opt-th-width-l">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30">
                                        <a href="{{URL::asset('/admin/xj/edit')}}?id={{$data->id}}">
                                            {{$data->title}}
                                        </a>
                                        </span></td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            {{$data->author}}
                                        </span>
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
                                            <a href="{{URL::asset('/admin/xj/setStatus')}}/{{$data->id}}?opt=1"
                                               class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="生效的宣教图文才可以推送给客户">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/xj/setStatus')}}/{{$data->id}}?opt=0"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="失效的宣教图文不会推送给客户">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/xj/edit')}}?id={{$data->id}}"
                                               class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="编辑该宣教图文">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/xj/del')}}/{{$data->id}}"
                                               class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="删除该宣教图文">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </a>
                                             <a href="{{URL::asset('/admin/xj/setStep')}}/{{$data->id}}"
                                                class="btn btn-social-icon btn-primary margin-right-10 opt-btn-size"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="设置宣教图文">
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
    </section>
@endsection

@section('script')
    <script type="application/javascript">

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })


    </script>
@endsection