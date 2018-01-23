@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">轮播管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建轮播
                </button>
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
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>内容</th>
                                <th>时间</th>
                                <th>录入人</th>
                                <th>宣教</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td>
                                        <div class="line-height-30 text-info text-oneline con-th-width-ll">
                                            {{$data->content}}
                                        </div>
                                    </td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                        @if ($data->doctor)
                                                {{$data->doctor->name}}
                                            @else
                                                未知
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                         <span class="line-height-30">
                                        @if ($data->xj)
                                                 {{$data->xj->name}}
                                             @else
                                                 未关联
                                             @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($data->status === '0')
                                            <span class="label label-default line-height-30">隐藏</span>
                                        @else
                                            <span class="label label-success line-height-30">展示</span>
                                        @endif

                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            <a href="{{URL::asset('/admin/ad/setStatus')}}/{{$data->id}}?opt=1"
                                               class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="在小程序页面中展示该轮播图">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/ad/setStatus')}}/{{$data->id}}?opt=0"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="在小程序页面中隐藏该轮播图">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="编辑该轮播图"
                                                  onclick="clickEdit({{$data->id}})">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <span class="btn btn-social-icon btn-danger opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该轮播图"
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
    <div class="modal fade -m" id="addADModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理轮播图</h4>
                </div>
                <form id="editAD" action="{{URL::asset('/admin/ad/edit')}}" method="post" class="form-horizontal"
                      onsubmit="return checkValid();">
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">id</label>
                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="doctor_id" class="col-sm-2 control-label">录入人id</label>
                                <div class="col-sm-10">
                                    <input id="doctor_id" name="doctor_id" type="text" class="form-control"
                                           value="{{$admin->id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">录入人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                           value="{{$admin->name}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="seq" class="col-sm-2 control-label">说明*</label>

                                <div class="col-sm-10">
                                    <input id="content" name="content" type="text" class="form-control"
                                           placeholder="轮播图说明"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="img" class="col-sm-2 control-label">图片*</label>

                                <div class="col-sm-10">
                                    <input id="img" name="img" type="text" class="form-control"
                                           placeholder="图片网路链接地址"
                                           value="">
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="text-center">
                                <div id="container">
                                    <img id="pickfiles"
                                         src="{{URL::asset('/img/upload.png')}}"
                                         style="width: 350px;">
                                </div>
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传350*200尺寸图片
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" id="addADModal_confirm_btn" data-value=""
                                class="btn btn-success">确定
                        </button>
                    </div>
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
                    <p>您确认要删除该轮播图片吗？</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="url"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button id="delConfrimModal_confirm_btn" data-value="" onclick="delAD();"
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

        //点击删除
        function clickDel(ad_id) {
            console.log("clickDel ad_id:" + ad_id);
            //为删除按钮赋值
            $("#delConfrimModal_confirm_btn").attr("data-value", ad_id);
            $("#delConfrimModal").modal('show');
        }

        //删除轮播
        function delAD() {
            var ad_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delAD ad_id:" + ad_id);
            //进行tr隐藏
            $("#tr_" + ad_id).fadeOut();
            //进行页面跳转
            window.location.href = "{{URL::asset('/admin/ad/del')}}/" + ad_id;
        }

        //点击新建轮播图
        function clickAdd() {
            //清空模态框
            $("#editAD")[0].reset();
            $("#admin_id").val("{{$admin->id}}")
            $("#pickfiles").attr("src", '{{URL::asset('/img/upload.png')}}');
            $("#addADModal").modal('show');
        }

        //点击编辑
        function clickEdit(ad_id) {
            console.log("clickEdit ad_id:" + ad_id);
            getADById("{{URL::asset('')}}", {id: ad_id}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#content").val(msgObj.content);
                    $("#img").val(msgObj.image);
                    $("#pickfiles").attr("src", msgObj.image);
                    //展示modal
                    $("#addADModal").modal('show');
                }
            })
        }

        //合规校验
        function checkValid() {
            console.log("checkValid");
            var content = $("#content").val();
            //合规校验
            if (judgeIsNullStr(content)) {
                $("#content").focus();
                return false;
            }
            var img = $("#img").val();
            if (judgeIsNullStr(img)) {
                $("#img").focus();
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
                        $("#img").val(sourceLink);
                        $("#pickfiles").attr('src', qiniuUrlTool(sourceLink, "ad"));
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