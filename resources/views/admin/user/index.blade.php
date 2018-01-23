@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">患者管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建患者信息
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{--条件搜索--}}
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="">
                    <!-- form start -->
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <input id="search_word" name="search_word" type="text" class="form-control"
                                       placeholder="根据用户名称/手机号码搜索" onchange="searchList(this)">
                            </div>
                            <div class="col-sm-4">
                                <select id="selecter" onchange="changeList(this)" class="form-control">
                                    <option value="0">全部</option>
                                    <option value="1">我的患者</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box -->
            </div>
        </div>

        <div id="UserList"></div>
        <div id="datas" style=" display:none;">
            @foreach($datas as $data)
                <div>{{$data}}</div>
            @endforeach
        </div>
        <div id="PageFoot"></div>
        <div class="modal fade -m" id="addUserModal" tabindex="-1" role="dialog">
        </div>
        <div id="Modal" tabindex="-1" role="dialog"></div>
    </section>

    <script id='UserList-content-template' type="text/x-dot-template">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>头像</th>
                                <th>姓名</th>
                                <th>性别</th>
                                <th>年龄</th>
                                <th>电话</th>
                                <th>手术名称</th>
                                <th>位置</th>
                                <th>康复医师</th>
                                <th class="opt-th-width">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @{{~it:data:index }}
                            <tr id="tr_@{{=data.id}}">
                                <td>
                                    <img src="@{{?data.avatar}}@{{=data.avatar }}@{{?? }}{{URL::asset('/img/default_headicon.png')}}@{{? }}"
                                         class="img-rect-30 radius-5">
                                </td>
                                <td>
                                    <span class="line-height-30">@{{=data.real_name}}</span>
                                </td>
                                <td><span class="line-height-30">
                                    @{{?data.gender == "0" }}保密@{{??data.gender == "1"}}男@{{??data.gender == "2"}}女@{{? }}</span>
                                </td>
                                <td>
                                    <span class="line-height-30">@{{?data.age }}@{{=data.age}}岁@{{?? }}--@{{? }}</span>
                                </td>
                                <td>
                                    <span class="line-height-30">@{{=data.phonenum}}</span>
                                </td>
                                <td>
                                    <div class="line-height-30 text-oneline con-th-width-m">
                                        @{{?data.userCase&&data.userCase.surgery }}
                                        @{{=data.userCase.surgery.name}}
                                        @{{?? }}
                                        --
                                        @{{? }}
                                    </div>
                                </td>
                                <td>
                                    <span class="line-height-30">@{{?data.userCase&&data.userCase.hpos}}@{{=data.userCase.hpos.name}}@{{?? }}--@{{? }}</span>
                                </td>
                                <td>
                                    <span class="line-height-30">@{{?data.userCase&&data.userCase.kf_doctor}}@{{=data.userCase.kf_doctor.name }}@{{?? }}--@{{? }}</span>
                                </td>
                                <td class="opt-th-width">
                                    <span class="line-height-30">
                                        <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                              onclick="clickEdit('@{{=data.id}}')"
                                              data-toggle="tooltip"
                                              data-placement="top"
                                              title="编辑该患者基本信息">
                                            <i class="fa fa-edit opt-btn-i-size"></i>
                                        </span>

                                        <a href="{{URL::asset('/admin/user/userCaseIndex')}}?user_id=@{{=data.id}}"
                                           class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           title="管理患者病例">
                                        <i class="fa fa-th-list opt-btn-i-size"></i>
                                        </a>
                                        <a href="{{URL::asset('/admin/user/userCJSJ')}}?user_id=@{{=data.id}}"
                                           class="btn btn-social-icon btn-danger opt-btn-size"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           title="管理患者数据">
                                            <i class="fa fa-database opt-btn-i-size"></i>
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
    </script>

    <script id="PageFoot-content-template" type="text/x-dot-template">
        <div class="row">
            <div class="col-sm-5">

            </div>
            <div id="page" class="col-sm-7 text-right">
                <button @{{?it.page==1 }}disabled='true' @{{? }}type="button" class="btn btn-primary"
                        onclick="changePage(-1)">
                    上一页
                </button>
                <span>第 @{{=it.page }} 页/共 @{{=it.length }} 页</span>
                <button @{{?(it.page>=it.length) }}disabled= 'true'@{{? }} type="button" class="btn
                    btn-primary"onclick="changePage(1)">
                    下一页
                </button>
            </div>
        </div>
    </script>
    <script id='Modal-content-template' type="text/x-dot-template">
        {{--新建对话框--}}

        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">新建/编辑</h4>
                </div>
                <form id="editUser" action="{{URL::asset('/admin/user/edit')}}" method="post"
                      class="form-horizontal"
                      onsubmit="return checkValid();">
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">id*</label>

                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           placeholder="自动生成id"
                                           value="@{{=it.id||'' }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="real_name" class="col-sm-2 control-label">姓名</label>
                                <div class="col-sm-10">
                                    <input id="real_name" name="real_name" type="text" class="form-control"
                                           placeholder="请输入患者名称"
                                           value="@{{=it.real_name||''}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="birthday" class="col-sm-2 control-label">出生日期</label>
                                <div class="col-sm-10">
                                    <input id="birthday" name="birthday" type="date" class="form-control"
                                           placeholder="请输入患者出生日期"
                                           value="@{{=it.birthday||''}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phonenum" class="col-sm-2 control-label">联系方式</label>
                                <div class="col-sm-10">
                                    <input id="phonenum" name="phonenum" type="text" class="form-control"
                                           placeholder="请输入手机号码"
                                           value="@{{=it.phonenum||''}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="gender" class="col-sm-2 control-label">性别</label>
                                <div class="col-sm-10">
                                    <select id="gender" name="gender" class="form-control">
                                        <option value="1" @{{=it.gender==='1'?'selected':'' }}>男</option>
                                        <option value="2" @{{=it.gender==='2'?'selected':'' }}>女</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" id="addUserModal_confirm_btn" data-value=""
                                class="btn btn-success">确定
                        </button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->

    </script>
@endsection

@section('script')
    <script type="application/javascript">
        var page = 1;
        var list = [];
        var List_view = [];//页面上显示的列表
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            var children = $("#datas").children();
            for (var x = 0; x < children.length; x++) {
                var a = JSON.parse(children[x].innerText);
                list.push(a);
            }
            List_view = list;
            console.log(List_view);
            LoadHtml(List_view);
        });

        function LoadHtml(LIST) {
            var List = LIST;
            console.log(parseInt(List.length / 10 + 1), List);
            if (List.length > 10) {
//                $("#pageFoot").html(page);
                console.log(page);
                $("#PageFoot").empty();
                var PageFoot = doT.template($("#PageFoot-content-template").text());
                $("#PageFoot").html(PageFoot({'page': page, 'length': parseInt(LIST.length / 10 + 1)}));
                LIST = LIST.slice((page - 1) * 10, page * 10)
            }

            $("#UserList").empty();
            var interText = doT.template($("#UserList-content-template").text());
            $("#UserList").html(interText(LIST));
        }

        function showModal(Data) {
            $("#addUserModal").empty();
            var interText = doT.template($("#Modal-content-template").text());
            $("#addUserModal").html(interText(Data));

        }

        function searchList(e) {
            page = 1;
            console.log(e, e.value)
            $("#selecter").val("0");
            var newList = [];
            var keyword = e.value;
            for (var x in list) {
                if (list[x].real_name.search(keyword) >= 0) {
                    newList.push(list[x]);
                }
                else if (list[x].phonenum.search(keyword) >= 0) {
                    newList.push(list[x]);
                }
            }
            List_view = newList;
            console.log(List_view);
            LoadHtml(List_view);
        }

        function changeList(e) {
            page = 1;
            var m = e.value;
            switch (m) {
                case "0":
                    searchList($("#search_word")[0]);
                    break;
                case "1":
                    console.log("my id:", "{{$admin->id}}")
                    var newList = [];
                    for (var x in List_view) {
                        if (List_view[x].userCase)
                            if (List_view[x].userCase.kf_doctor_id == "{{$admin->id}}") {
                                newList.push(list[x]);
                            }
                    }
                    List_view = newList;
                    LoadHtml(List_view)
                    break;
            }
        }

        //合规校验
        function checkValid() {
            var real_name = $("#real_name").val();
            //合规校验
            if (judgeIsNullStr(real_name)) {
                $("#real_name").focus();
                return false;
            }
            var birthday = $("#birthday").val();
            if (judgeIsNullStr(birthday)) {
                $("#birthday").focus();
                return false;
            }
            var phonenum = $("#phonenum").val();
            if (judgeIsNullStr(phonenum)) {
                $("#phonenum").focus();
                return false;
            }
            return true;
        }

        //点击新建康复患者信息
        function clickAdd() {
            //清空模态框
            var data = {'doctor_id': "{{$admin->id}}"}
            showModal(data);
            $("#addUserModal").modal('show');
        }

        //点击编辑患者信息
        function clickEdit(user_id) {
            console.log("clickEdit user_id:" + user_id);
            getUserById("{{URL::asset('')}}", {id: user_id}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    showModal(msgObj);
                    //展示modal
                    $("#addUserModal").modal('show');
                }
            })
        }

        function changePage(delta) {
            page += parseInt(delta);
            LoadHtml(List_view)
        }


    </script>
@endsection