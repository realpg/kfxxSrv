@extends('admin.layouts.app')

@section('content')
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
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建医师/康复师/管理员
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
                    <form action="{{URL::asset('/admin/doctor/search')}}" method="post" class="form-horizontal">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input id="search_word" name="search_word" type="text" class="form-control"
                                           placeholder="根据用户名称/手机号码搜索"
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
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
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
                                <tr id="tr_{{$data->id}}">
                                    <td>
                                        <img src="{{ $data->avatar ? $data->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             class="img-rect-30 radius-5">
                                    </td>
                                    <td><span class="line-height-30 text-info">
                                            {{$data->name}}
                                        </span></td>
                                    <td><span class="line-height-30">{{$data->dep}}</span></td>
                                    <td><span class="line-height-30">{{$data->duty}}</span></td>
                                    <td>
                                        <span class="line-height-30">
                                        @if($data->role==='0')
                                                <span class="label label-info line-height-30">管理人员</span>
                                            @endif
                                            @if($data->role==='1')
                                                <span class="label label-success line-height-30">医师人员</span>
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
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  onclick="clickEdit({{$data->id}})"
                                                  title="编辑该角色">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <span class="btn btn-social-icon btn-danger opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该角色"
                                                  onclick="clickDel({{$data->id}})">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </span>
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

    {{--新建对话框--}}
    <div class="modal fade -m" id="addDoctorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理人员（初始密码：Aa123456）</h4>
                </div>
                <form id="editDoctor" action="{{URL::asset('/admin/doctor/edit')}}" method="post"
                      class="form-horizontal"
                      onsubmit="return checkValid();">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">*id</label>
                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           placeholder="自动生成id"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">姓名*</label>
                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" class="form-control"
                                           placeholder="请输入姓名"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="phonenum" class="col-sm-2 control-label">电话*</label>

                                <div class="col-sm-10">
                                    <input id="phonenum" name="phonenum" type="text" class="form-control"
                                           value=""
                                           placeholder="请输入电话号码">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="gender" class="col-sm-2 control-label">性别*</label>

                                <div class="col-sm-10">
                                    <select id="gender" name="gender" class="form-control">
                                        <option value="0">保密</option>
                                        <option value="1">男</option>
                                        <option value="2">女</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="avatar" class="col-sm-2 control-label">头像*</label>

                                <div class="col-sm-10">
                                    <input id="avatar" name="avatar" type="text" class="form-control"
                                           placeholder="图片网路链接地址"
                                           value="">
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="text-center">
                                <div id="container">
                                    <img id="pickfiles"
                                         src="{{ URL::asset('/img/default_headicon.png')}}"
                                         style="width: 120px;height: 120px;border-radius: 50%;">
                                </div>
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传200*200尺寸图片</div>
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="role" class="col-sm-2 control-label">角色*</label>

                                <div class="col-sm-10">
                                    <select id="role" name="role" class="form-control"
                                            value="">
                                        <option value="0">管理人员</option>
                                        <option value="1">医师人员</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" id="addDoctorModal_confirm_btn" data-value=""
                                class="btn btn-success">确定
                        </button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    {{--删除对话框--}}
    <div class="modal fade " id="delConfrimModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">提示信息</h4>
                </div>
                <div class="modal-body">
                    <p>您确认要删除该医师信息吗？</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="url"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button id="delConfrimModal_confirm_btn" data-value="" onclick="delDoctor();"
                            class="btn btn-success"
                            data-dismiss="modal">确定
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('script')
    <script type="application/javascript">

        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            //获取七牛token
            initQNUploader();
        });

        //点击删除医师
        function clickDel(doctor_id) {
            console.log("clickDel doctor_id:" + doctor_id);
            //为删除按钮赋值
            $("#delConfrimModal_confirm_btn").attr("data-value", doctor_id);
            $("#delConfrimModal").modal('show');
        }

        //删除医师
        function delDoctor() {
            var doctor_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delDoctor doctor_id:" + doctor_id);
            //进行tr隐藏
            $("#tr_" + doctor_id).fadeOut();
            //进行页面跳转
            window.location.href = "{{URL::asset('/admin/doctor/del')}}/" + doctor_id;
        }

        //点击新建医师
        function clickAdd() {
            //清空模态框
            $("#editDoctor")[0].reset();
            $("#pickfiles").attr("src", '{{URL::asset('/img/default_headicon.png')}}');
            $("#addDoctorModal").modal('show');
        }

        //点击编辑
        function clickEdit(doctor_id) {
            console.log("clickEdit doctor_id:" + doctor_id);
            getDoctorById("{{URL::asset('')}}", {id: doctor_id}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);
                    $("#phonenum").val(msgObj.phonenum);
                    $("#gender").val(msgObj.gender);
                    $("#avatar").val(msgObj.avatar)
                    $("#pickfiles").attr("src", msgObj.avatar);
                    $("#duty").val(msgObj.duty);
                    $("#dep").val(msgObj.dep)
                    $("#role").val(msgObj.role);
                    //展示modal
                    $("#addDoctorModal").modal('show');
                }
            })
        }

        //合规校验
        function checkValid() {
            //合规校验
            var name = $("#name").val();
            if (judgeIsNullStr(name)) {
                $("#name").focus();
                return false;
            }
            var phonenum = $("#phonenum").val();
            if (judgeIsNullStr(phonenum) || !isPoneAvailable(phonenum)) {
                $("#phonenum").focus();
                return false;
            }
            var avatar = $("#avatar").val();
            if (judgeIsNullStr(avatar)) {
                $("#avatar").focus();
                return false;
            }
            return true;
        }


        //初始化七牛上传模块
        function initQNUploader() {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: 'pickfiles',         // 上传选择的点选按钮，必需
                container: 'container',//上传按钮的上级元素ID
                // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
                // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
                // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
                uptoken: "{{$upload_token}}", // uptoken是上传凭证，由其他程序生成
                // uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
                // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
                //    // do something
                //    return uptoken;
                // },
                get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                // downtoken_url: '/downtoken',
                // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
                unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
                // save_key: true,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
                domain: 'http://twst.isart.me/',     // bucket域名，下载资源时用到，必需
                max_file_size: '100mb',             // 最大文件体积限制
                flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                dragdrop: true,                     // 开启可拖曳上传
                drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                //x_vars : {
                //    查看自定义变量
                //    'time' : function(up,file) {
                //        var time = (new Date()).getTime();
                // do something with 'time'
                //        return time;
                //    },
                //    'size' : function(up,file) {
                //        var size = file.size;
                // do something with 'size'
                //        return size;
                //    }
                //},
                init: {
                    'FilesAdded': function (up, files) {
                        plupload.each(files, function (file) {
                            // 文件添加进队列后，处理相关的事情
//                                            alert(alert(JSON.stringify(file)));
                        });
                    },
                    'BeforeUpload': function (up, file) {
                        // 每个文件上传前，处理相关的事情
//                        console.log("BeforeUpload up:" + up + " file:" + JSON.stringify(file));
                    },
                    'UploadProgress': function (up, file) {
                        // 每个文件上传时，处理相关的事情
//                        console.log("UploadProgress up:" + up + " file:" + JSON.stringify(file));
                    },
                    'FileUploaded': function (up, file, info) {
                        // 每个文件上传成功后，处理相关的事情
                        // 其中info是文件上传成功后，服务端返回的json，形式如：
                        // {
                        //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                        //    "key": "gogopher.jpg"
                        //  }
//                        console.log(JSON.stringify(info));
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        //获取上传成功后的文件的Url
                        var sourceLink = domain + res.key;
                        $("#avatar").val(sourceLink);
                        $("#pickfiles").attr('src', qiniuUrlTool(sourceLink, "head_icon"));
//                        console.log($("#pickfiles").attr('src'));
                    },
                    'Error': function (up, err, errTip) {
                        //上传出错时，处理相关的事情
                        console.log(err + errTip);
                    },
                    'UploadComplete': function () {
                        //队列文件处理完毕后，处理相关的事情
                    },
                    'Key': function (up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在unique_names: false，save_key: false时才生效

                        var key = "";
                        // do something with key here
                        return key
                    }
                }
            });
        }

    </script>
@endsection