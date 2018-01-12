@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">编辑量表</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="submitAll()">
                    保存量表
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box-body">
            <div id="token">{{csrf_field()}}</div>
            <div id="LBinfo">

            </div>
            <div id="message-content">

            </div>
        </div>
    </section>

    <script id="LBinfo-content-template" type="text/x-dot-template">
        <div class="white-bg">
            <div style="padding: 15px;">
                <h2 class="font-size-16 text-info">
                    @{{=it.name}}
                </h2>
                <div class="margin-top-10 font-size-14 grey-bg">
                    <div style="padding: 10px;">
                        @{{=it.desc}}
                    </div>
                </div>
                <div class="margin-top-10  font-size-14">
                    点击查看：<a href="{{URL::asset('/admin/xj/editXJ')}}?id=@{{=it.xj_id}}" target="_blank"><span
                                class="text-aqua">宣教信息<i
                                    class="fa fa-link margin-left-5"></i></span></a>
                </div>
            </div>
        </div>

    </script>


    <script id="message-content-template" type="text/x-dot-template">

        <div class="row margin-top-10">
            <div class="col-md-12">
                @{{~it :question:index_que}}
                <div class="white-bg">
                    <div style="padding: 15px;">
                    {{--@{{~question.options :option:index_opt}}--}}
                    <!-- row -->
                        <div class="row margin-top-10">
                            <div class="col-md-12">
                                <!-- The time line -->

                                <h3>
                                    <input onchange="changeQuestion('@{{=index_que}}',this)" type="text"
                                           class="form-control" placeholder="请输入问题"
                                           value=@{{=question.question}}>
                                </h3>
                                <table class="table table-bordered">


                                    <tr class="margin-top-10 grey-bg">
                                        <th></th>
                                        <th class="col-md-1">序号</th>
                                        <th class="col-md-9">选项</th>
                                        <th class="col-md-1">分数</th>
                                        <th class="col-md-1">操作</th>
                                    </tr>
                                    @{{~question.options :option:index_opt}}
                                    <tr class="row">
                                        <th>
                                            @{{=index_opt}}
                                        </th>

                                        <th>

                                            <input id="opt@{{=index_opt}}"
                                                   onchange="changeOption('@{{=index_que}}','@{{=index_opt}}',this)"
                                                   style="border: none;width: 100%"
                                                   type="text" placeholder="请输入选项"
                                                   value=@{{=option.option }}>
                                        </th>


                                        <th>
                                            <input id="point@{{=index_opt}}"
                                                   onchange="changePoint('@{{=index_que}}','@{{=index_opt}}',this)"
                                                   type="number" style="border: none"
                                                   value=@{{=option.point }}>
                                        </th>
                                        <th>
                                            <i class="fa fa-minus-circle text-info  btn"
                                               onclick="delOpt('@{{=index_que}}','@{{=index_opt}}');"></i>
                                        </th>


                                    </tr>
                                    @{{~}}


                                </table>
                            </div>

                            <div class="col-md-12">
                                <div style="padding: 10px;" class="text-info btn  inline">
                                    <i class="fa fa-plus-circle"></i>
                                    <span class="margin-left-15"
                                          onclick="addOpt('@{{=index_que}}')">添加选项</span>
                                </div>

                                <div class="text-right pull-right inline"><img
                                            src="{{URL::asset('/img/up_pointer_icon.png')}}"
                                            class="opt-btn-size margin-right-10"
                                            onclick="moveQue('@{{=index_que}}',-1)"> <img
                                            src="{{URL::asset('/img/down_pointer_icon.png')}}"
                                            class="opt-btn-size margin-right-10" onclick="moveQue('@{{=index_que}}',1)">
                                    <img
                                            src="{{URL::asset('/img/delete_icon.png')}}"
                                            class="opt-btn-size" onclick=""></div>
                            </div>


                        </div>

                        {{--@{{~}}--}}
                    </div>
                </div>
                @{{~}}
                <div class="text-center margin-top-10">
                    <img src="{{URL::asset('/img/add_button_icon.png')}}"
                         style="width: 36px;height: 36px;"
                         onclick="addQue()">
                </div>
            </div>
            <!-- /.col -->
        </div>


    </script>


    <!--新建编辑宣教步骤对话框-->
    <div class="modal fade -m" id="editJHModal" tabindex="-1" role="dialog">


    </div>

    <script id="editJHModal-content-template" type="text/x-dot-template">

    </script>


    <!--数据采集模板-->
    <div class="modal fade -m" id="editSJModal" tabindex="-1" role="dialog">

    </div>


    <script id="editSJModal-content-template" type="text/x-dot-template">

    </script>


    <!--提示modal-->
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
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection


@section('script')
    <script type="application/javascript">
        var lb;
        var questions = [];
        var deleted = [];
        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            var lb_id = getQueryString("id");
            //从url中获取id
            if (judgeIsNullStr(lb_id)) {
                //没有id则报错
                alert("康复模板id为空，请联系管理员处理");
                return;
            } else {
                var param = {
                    id: lb_id,
                }
                getLBById("{{URL::asset('')}}", param, function (ret, err) {
                    //提示保存成功
                    if (ret.result == true) {
                        lb = ret.ret;
                        questions = unzip(lb.questions);
                        loadHtml();
                    }
                    else {
                        alert(ret.ret);
                    }
                })
            }
        });

        //处理接收到的问题数据,将answer字符串转化为options对象
        function unzip(Questions) {
            for (var i in Questions) {
                Questions[i].options = [];
                var opts = Questions[i].answer.split("@q=");
                for (var j = 1; j < opts.length; j++) {
                    Questions[i].options.push({
                            option: opts[j].split('&p=')[0],
                            point: opts[j].split('&p=')[1]
                        }
                    );
                }
            }
            return Questions;
        }

        //将options对象转换为字符串
        function zip(Questions) {
            for (var i in Questions) {
                Questions[i].seq = i;
                Questions[i].lb_id = lb.id;
                Questions[i].answer = "";
                for (var j in Questions[i].options) {
                    Questions[i].answer += "@q=" + Questions[i].options[j].option + "&p=" + Questions[i].options[j].point;
                }
            }
            return Questions;
        }

        //加载界面
        function loadHtml() {
            //清理页面
            $("#message-content").empty();
            //整理数据
            //加载页面
            var interText = doT.template($("#message-content-template").text());
            $("#message-content").html(interText(questions));
            var interText2 = doT.template($("#LBinfo-content-template").text());
            $("#LBinfo").html(interText2(lb));
        }

        function addOpt(index) {

            questions[index].options.push({option: "", point: 0})
            loadHtml();
        }

        function addQue(index_que) {
            questions.push({
                question: "",
                options: [{option: "", point: 0}]
            });
            loadHtml();
        }

        function moveQue(index_que, a) {

            var x = index_que;
            var y = parseInt(index_que) + a;
            var l = questions.length;
            if (x >= 0 && y >= 0 && x < l && y < l) {
                var m = questions[x];
                questions[x] = questions[y];
                questions[y] = m;
                console.log(JSON.stringify(questions));
                loadHtml();
            }
        }

        function changeQuestion(index_que, e) {
            questions[index_que].question = e.value;
            loadHtml();
        }

        function changeOption(index_que, index_opt, e) {

            questions[index_que].options[index_opt].option = e.value;
            loadHtml();
        }

        function delQue(index_que) {
            if (questions[index_que].id)
                deleted.push(questions[index_que].id);
            questions.splice(index_que, 1);
            loadHtml();
        }

        function delOpt(index_que, index_opt) {
            questions[index_que].options.splice(index_opt, 1);
            loadHtml();
        }

        function changePoint(index_que, index_opt, e) {
            questions[index_que].options[index_opt].point = e.value;
            loadHtml();
        }

        function changeName(e) {
            lb.name = e.value;
            loadHtml();
        }

        function changeDesc(e) {
            lb.desc = e.value;
            loadHtml();
        }

        function submitAll() {
            lb.doctor_id ={{$admin->id}};
            var q = zip(questions);
            lb.questions = q;
            var s = JSON.stringify(lb)
            console.log(s, deleted);
            lb.doctor_id ={{$admin->id}};
            lb.questions = q;
            lb.deleted = deleted;
            //获取tokenn
            var token = $("#token").children().val();
            lb._token = token;
            console.log("submitAll lb:" + JSON.stringify(lb));
            editLB("{{URL::asset('')}}", JSON.stringify(lb), function (ret, err) {
                console.log(JSON.stringify(ret))

                alert("提交成功")
            })
        }

    </script>
@endsection