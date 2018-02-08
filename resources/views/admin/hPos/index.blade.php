@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">患处位置管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建患处位置
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
                                <th>ID</th>
                                <th>位置名称</th>
                                <th>位置数量</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30">{{$data->id}}</span></td>
                                    <td><span class="line-height-30">{{$data->name}}</span></td>
                                    <td><span class="line-height-30">{{$data->number}}</span></td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span></td>
                                    <td>
                                        <span class="line-height-30">
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="编辑患处位置"
                                                  onclick="clickEdit({{$data->id}})">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>

                                            <span class="btn btn-social-icon btn-danger opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除患处位置"
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
    </section>
    {{--新建对话框--}}
    <div class="modal fade -m" id="editHposModal" tabindex="-1" role="dialog">

    </div><!-- /.modal -->

    <script id="hPos-content-template" type="text/x-dot-template">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理患处位置</h4>
                </div>
                <form id="editHPos" class="form-horizontal">
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">id</label>
                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           value="@{{=it.id}}">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="title" class="col-sm-2 control-label">录入人id</label>
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
                                <label for="name" class="col-sm-2 control-label">名称*</label>

                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" class="form-control"
                                           placeholder="患处位置名称"
                                           value="@{{=it.name}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="number" class="col-sm-2 control-label">位置数*</label>

                                <div class="col-sm-10">
                                    <input id="number" name="number" type="number" class="form-control"
                                           placeholder="患处位置数"
                                           value="@{{=it.number}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="seq" class="col-sm-2 control-label">展示顺序*</label>

                                <div class="col-sm-10">
                                    <input id="seq" name="seq" type="number" class="form-control"
                                           placeholder="值越大越靠前"
                                           value="@{{=it.seq}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="seq" class="col-sm-2 control-label"></label>

                                <div class="col-sm-10">
                                    <div id="container">


                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-success" onclick="clickSave();">确定
                        </button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </script>

    <script id="hPos-img-content-template" type="text/x-dot-template">
        @{{for(var i=0;i<it.img_arr.length;i++){}}
        <img id="@{{=i}}" class="margin-top-10"
             src="@{{=it.img_arr[i]}}?imageView2/2/w/120/h/70/interlace/1/q/75|imageslim"
             style="width: 120px;height: 70px;">
        <div onclick="delImg(@{{=i}})">删除</div>
        @{{}}}
        <img id="pickfiles" class="margin-top-10"
             src="{{URL::asset('/img/upload.png')}}"
             style="width: 120px;height: 70px;">
    </script>



    {{--删除对话框--}}
    <div class="modal fade " id="tip_modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">提示信息</h4>
                </div>
                <div class="modal-body">
                    <div>由于患处位置关联了较多信息，因此需要慎重删除，请联系系统管理员进行咨询和处理</div>
                    <div class="margin-top-10">
                        <a href="">患处位置的作用，以及如何管理患处位置</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>


    {{--提示Modal--}}
    <div class="modal fade" id="tipModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">提示信息</h4>
                </div>
                <div class="modal-body" id="tipModalBody">

                </div>
                <div class="modal-footer">
                    <button id="delConfrimModal_confirm_btn" data-value=""
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
        function clickDel(hPos_id) {
            console.log("clickDel hPos_id:" + hPos_id);
            //为删除按钮赋值
            $("#tip_modal").modal('show');
        }

        //删除患处位置
        function delHPos() {
            var hPos_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delHPos hPos_id:" + hPos_id);
            //进行tr隐藏
            $("#tr_" + hPos_id).fadeOut();
            //进行页面跳转
            window.location.href = "{{URL::asset('/admin/hPos/del')}}/" + hPos_id;
        }

        //空hPos_info信息
        var empty_hPos_info = {
            id: "",
            name: "",
            number: "",
            img: "",
            img_arr: [],
            seq: ""
        }
        //配置hPos_info信息
        var hPos_info = {}

        //加载页面
        function loadHtml() {
            //基本信息
            var interText = doT.template($("#hPos-content-template").text());
//            alert(interText(empty_hPos_info));
            $("#editHposModal").html(interText(hPos_info));
            //完成基本页面加载后加载图片页面
            loadImgHtml();
        }

        //加载图片
        function loadImgHtml() {
            var img_interText = doT.template($("#hPos-img-content-template").text());
            $("#container").html(img_interText(hPos_info));
            //初始化七牛云存储
            initQNUploader();
        }

        //点击新建患处位置图
        function clickAdd() {
            hPos_info = clone(empty_hPos_info);
            loadHtml(hPos_info);
            $("#editHposModal").modal('show');
        }

        //点击编辑
        function clickEdit(hPos_id) {
            console.log("clickEdit hPos_id:" + hPos_id);
            getHPosById("{{URL::asset('')}}", {id: hPos_id}, function (ret) {
                if (ret.result) {
                    //对象配置
                    hPos_info = ret.ret;
                    if (hPos_info.img)
                    hPos_info.img_arr = hPos_info.img.split(",");
                    else
                        hPos_info.img_arr=[];
                    loadHtml(hPos_info);
                    //展示modal
                    $("#editHposModal").modal('show');
                }
            })
        }

        function delImg(i){
            hPos_info.img_arr.splice(i,1);
            loadHtml();
        }
        //点击保存
        function clickSave() {
            console.log("clickSave");
            //合规校验
            var name = $("#name").val();
            if (judgeIsNullStr(name)) {
                $("#name").focus();
                return;
            }
            var number = $("#number").val();
            if (judgeIsNullStr(number)) {
                $("#number").focus();
                return;
            }
            var seq = $("#seq").val();
            if (judgeIsNullStr(seq)) {
                $("#seq").focus();
                return;
            }
//            if (hPos_info.img_arr.length <= 0) {
//                $("#tipModalBody").html('<p>请上传患处位置的图片</p>');
//                $("#tipModal").modal('show');
//                return;
//                return;
//            }
            //赋值准备保存
            hPos_info.img = hPos_info.img_arr.toString();
            hPos_info.name = name;
            hPos_info.number = number;
            hPos_info.seq = seq;
            hPos_info.id = $("#id").val();
            hPos_info._token = "{{ csrf_token() }}";
            console.log("hPos_info:" + JSON.stringify(hPos_info));

            editHPos("{{URL::asset('')}}", JSON.stringify(hPos_info), function (ret, err) {
                //提示保存成功
                if (ret.result == true) {
                    $("#editHposModal").hide();
                    $("#tipModalBody").html('<p>患者位置保存成功</p>');
                    $("#tipModal").modal('show');
                    location.reload();  //重新加载页面
                } else {
                    $("#tipModalBody").html("<p>宣教信息保存失败，请联系<span class='text-info'>管理员处理</span></p>");
                    $("#tipModal").modal('show');
                }
            })
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
                        //赋值并加载页面
                        hPos_info.img_arr.push(sourceLink);
                        console.log("hPos_info:" + JSON.stringify(hPos_info));
                        loadImgHtml();
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