@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">回答记录</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <select onchange="changeList(this)">
                    <option value="0">全部</option>
                    <option value="1">全部未处理记录</option>
                    <option value="2">我的已处理记录</option>
                    {{--<option value="3">我的患者未处理记录</option>--}}
                </select>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="AnswerList"></div>
        <div id="datas" style=" display:none;">
            @foreach($datas as $data)
                <div>{{$data}}</div>
            @endforeach
        </div>
        <div id="PageFoot"></div>

    </section>

    <script id="PageFoot-content-template" type="text/x-dot-template">
        <div class="row">
            <div class="col-sm-5">

            </div>
            <div id="page" class="col-sm-7 text-right">
                <button @{{?it.page<=1 }}disabled= 'true'@{{? }}type="button" class="btn btn-primary" onclick="changePage(-1)">
                    上一页
                </button>
                <span>第 @{{=it.page }} 页/共 @{{=it.length }} 页</span>
                <button @{{?(it.page>=it.length) }}disabled= 'true'@{{? }} type="button" class="btn btn-primary"
                        onclick="changePage(1)">
                    下一页
                </button>
            </div>
        </div>
    </script>
    <script id="AnswerList-content-template" type="text/x-dot-template">
        <!--列表-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>量表名称</th>
                                <th>类型</th>
                                <th>用户</th>
                                <th>时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @{{~it:data:index }}
                            <tr style="word-break:break-all;width: 17%" id="tr_@{{=data.id}}">
                                <td>
                                    <div class="line-height-30  text-oneline ">
                                        @{{=data.lb.name}}
                                    </div>
                                </td>
                                <td style="width: 8%">
                                    <div class="line-height-30  text-oneline ">
                                        @{{? data.lb.type == '0'}}
                                        普通量表
                                        @{{?? data.lb.type == '1'}}
                                        验证量表
                                        @{{?? }}
                                        随访量表
                                        @{{? }}
                                    </div>
                                </td>

                                <td>
                                    <a href="{{URL::asset('/admin/user/userCaseIndex')}}/?user_id=@{{=data.user_id}}">
            <span class="line-height-30">
                @{{? data.user}}
                        @{{=data.user.real_name}}
                @{{?? }}
                    未知
                @{{? }}
            </span>
                                    </a>
                                </td>

                                <td style="word-break:break-all;width: 17%"><span
                                            class="line-height-30">@{{=data.created_at_str}}</span>
                                </td>

                                <td>
                                    @{{?data.status == '0' }}
                                    <span class="label label-default line-height-30">未处理</span>
                                    @{{?? }}
                                    <span class="label label-success line-height-30">已处理</span>
                                    @{{? }}

                                </td>

                                <td width="5%">
            <span class="line-height-30">

                {{--<span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"--}}
                {{--data-toggle="tooltip"--}}
                {{--data-placement="top"--}}
                {{--title="评分"--}}
                {{--onclick="clickEdit({{$data->id}})">--}}
                {{--<i class="fa fa-edit opt-btn-i-size"></i>--}}
                {{--</span>--}}
                {{--<span class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"--}}
                {{--data-toggle="tooltip"--}}
                {{--data-placement="top"--}}
                {{--title="删除该量表"--}}
                {{--onclick="clickDel({{$data->id}})">--}}
                {{--<i class="fa fa-trash-o opt-btn-i-size"></i>--}}
                {{--</span>--}}
                <a href="{{URL::asset('/admin/lb/editHistory')}}/?id=@{{=data.id}}"
                   class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                   data-toggle="tooltip"
                   data-placement="top"
                   title="评分">
            <i class="fa fa-edit opt-btn-i-size"></i>
            </a>
            </span>
                                </td>
                            </tr>
                            @{{~ }}
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

    </script>

@endsection

@section('script')
    <script type="application/javascript">
        var page = 1;
        var list = [];
        var newList = [];
        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            var children = $("#datas").children();
            for (var x = 0; x < children.length; x++) {
                var a = JSON.parse(children[x].innerText);
                list.push(a);
            }
            newList = list
            loadHtml(newList);
        });

        function loadHtml(List) {
            var LIST = List;
            console.log(parseInt(List.length / 10 + 1));
            $("#PageFoot").empty();
            if (List.length > 10) {
                $("#page").html(page);

                var PageFoot = doT.template($("#PageFoot-content-template").text());
                $("#PageFoot").html(PageFoot({'page': page, 'length': parseInt(List.length / 10 + 1)}));
                LIST = LIST.slice((page - 1) * 10, page * 10)
            }

            $("#AnswerList").empty();
            var interText = doT.template($("#AnswerList-content-template").text());
            $("#AnswerList").html(interText(LIST));

        }

        function changeList(e) {
            page = 1;
            var m = e.value;
            switch (m) {
                case "0":
                    newList = list;
                    loadHtml(list);
                    break;
                case "1":
                    changeList1(['status'], [0])
                    break;
                case "2":
                    changeList1(['doctor_id'], [{{$admin->id}}])
                    break;
            {{--case "3":--}}
                    {{--changeList1(['doctor_id', 'status'], [{{$admin->id}}, 0])--}}
                    {{--break;--}}
            }
        }

        function changeList1(keys, values) {
            newList = [];
            for (var x in list) {
                var flag = true;
                for (var y in keys)
                    if (list[x][keys[y]] != values[y])
                        flag = false;
                if (flag)
                    newList.push(list[x]);
            }
            loadHtml(newList)
        }

        function changePage(delta) {
            page += parseInt(delta);
            loadHtml(newList)
        }

    </script>
@endsection