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
                {{--<div class="margin-top-10  font-size-14">--}}
                    {{--点击查看：<a href="{{URL::asset('/admin/xj/editXJ')}}?id=@{{=it.xj_id}}" target="_blank"><span--}}
                                {{--class="text-aqua">宣教信息<i--}}
                                    {{--class="fa fa-link margin-left-5"></i></span></a>--}}
                {{--</div>--}}
            </div>
        </div>

    </script>


    <script id="message-content-template" type="text/x-dot-template">

        @{{~it :question:index_que}}
        <div class="row margin-top-20">
            <div class="col-md-12">



                <div class="white-bg">
                    <div style="padding: 15px;">
                    {{--@{{~question.options :option:index_opt}}--}}
                    <!-- row -->
                        <div class="row margin-top-10">
                            <div class="col-md-12">
                                <!-- The time line -->
                                <h3 class="form-control">
                                    问题:
                                    <input onchange="changeQuestion('@{{=index_que}}',this)" type="text"
                                           placeholder="请输入问题" style="border: none;width: 60%"
                                           value=@{{=question.question}}>
                                    @{{? question.type=='0'||question.type=='1'}}
                                    权重:
                                    <input
                                            onchange="changequan('@{{=index_que}}',this)"
                                            type="number" style="border: none" min="0"
                                            value=@{{=question.quan }}>
                                    @{{? }}
                                    <span class="pull-right">
                                    问题类型:
                                    <select name="type" style="border: none" value="@{{=question.type}}"
                                            onchange="changeType('@{{=index_que}}',this)">
                                        @{{? question.type=='0'}}
                                        <option value="0" selected='selected'>单选题</option>
                                        @{{?? }}
                                        <option value="0">单选题</option>
                                        @{{?}}
                                        @{{? question.type=='1'}}
                                        <option value="1" selected='selected'>多选题</option>
                                        @{{?? }}
                                        <option value="1">多选题</option>
                                        @{{?}}
                                        @{{? question.type=='2'}}
                                        <option value="2" selected='selected'>填空题</option>
                                        @{{?? }}
                                        <option value="2">填空题</option>
                                        @{{?}}
                                        @{{? question.type=='3'}}
                                        <option value="3" selected='selected'>表格题</option>
                                        @{{?? }}
                                        <option value="3">表格题</option>
                                        @{{?}}
                                    </select>
                                        </span>
                                </h3>
                                @{{? question.type=='0'||question.type=='1'}}
                                <table class="table table-bordered">
                                    <tr class="margin-top-10 grey-bg">
                                        <th></th>
                                        <th class="col-md-1">序号</th>
                                        <th class="col-md-9">选项</th>
                                        <th class="col-md-1">分数</th>
                                        <th class="col-md-1">最终分数</th>
                                        <th class="col-md-1">操作</th>
                                    </tr>
                                    @{{~question.options :option:index_opt}}
                                    <tr class="row">
                                        <th>
                                            @{{=index_opt+1}}
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
                                                   onchange="changePoint0('@{{=index_que}}','@{{=index_opt}}',this)"
                                                   type="number" style="border: none" min="0"
                                                   value=@{{=option.point0 }}>
                                        </th>

                                        <th>
                                            <input id="point@{{=index_opt}}"
                                                   onchange="changePoint('@{{=index_que}}','@{{=index_opt}}',this)"
                                                   type="number" style="border: none" disabled="true" min="0"
                                                   value=@{{=option.point }}>
                                        </th>
                                        <th>
                                            <i class="fa fa-minus-circle text-info  btn"
                                               onclick="delOpt('@{{=index_que}}','@{{=index_opt}}');"></i>
                                        </th>


                                    </tr>
                                    @{{~}}


                                </table>
                                @{{?? question.type=='2'}}
                                请在上方填写题目内容,使用半角字符"()"代替空格。具体分数将会由后台评判，请填写分数最大值:
                                <input type="number" min="0" value="@{{=question.answer }}"
                                       onchange="changePoint_b('@{{=index_que}}',this)">
                                @{{?? question.type=='3'}}
                                <table class="table table-bordered">
                                    <tr class="margin-top-10 grey-bg">
                                        <th style="width: 60px">问题</th>
                                        @{{~question.options:val_opt:index_q_opt1 }}
                                        <th style="width: 60px">
                                            <input style="width: 60px" value="@{{= val_opt}}"
                                                   onchange="changeOpt_q('@{{=index_que}}','@{{=index_q_opt1 }}',this)">
                                        </th>
                                        @{{~ }}
                                        <th onclick="addOpt_q('@{{=index_que}}')">添加选项</th>
                                    </tr>
                                    @{{~question.questions:val_que:index_q_que }}
                                    <tr>
                                        <th>
                                            <input style="width: 120px" value="@{{=val_que}}"
                                                   onchange="changeQue_q('@{{=index_que}}','@{{=index_q_que }}',this)">
                                        </th>
                                        @{{~question.options:val_opt:index_q_opt }}
                                        <th>
                                            {{--@{{= question.points[index_q_que][index_q_opt] }}--}}
                                            <input value="@{{= question.points[index_q_que][index_q_opt] }}"
                                                   style="width: 60px" min="0"
                                                   {{--onchange="changePoint_q('@{{=index_que}}','@{{index_q_que}}','@{{index_q_opt}}',this)"--}}
                                                   type="number"
                                                   onchange="changePoint_q('@{{=index_que}}','@{{=index_q_que}}','@{{=index_q_opt}}',this)">
                                        </th>
                                        @{{~ }}
                                    </tr>

                                    @{{~ }}
                                    <tr>
                                        <th onclick="addQue_q('@{{=index_que}}')">添加问题</th>
                                    </tr>
                                </table>
                                @{{?}}

                            </div>

                            <div class="col-md-12">
                                @{{? question.type=='0'||question.type=='1'}}
                                <div style="padding: 10px;" class="text-info btn  inline">
                                    <i class="fa fa-plus-circle"></i>
                                    <span class="margin-left-15"
                                          onclick="addOpt('@{{=index_que}}')">添加选项</span>
                                </div>
                                @{{?}}
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


            </div>
            <!-- /.col -->
        </div>
        @{{~}}
        <div class="text-center margin-top-10">
            <img src="{{URL::asset('/img/add_button_icon.png')}}"
                 style="width: 36px;height: 36px;"
                 onclick="addQue()">
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
            $(".sidebar-menu li:nth-child(7)").addClass("active");
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
                if (Questions[i].type == 0 || Questions[i].type == 1) {
                    Questions[i].options = [];
                    var opts = Questions[i].answer.split("@q=");
                    Questions[i].quan = 1;
                    for (var j = 1; j < opts.length; j++) {
                        Questions[i].options.push({
                                option: opts[j].split('&p=')[0],
                                point: opts[j].split('&p=')[1],
                                point0: opts[j].split('&p=')[1],
                            }
                        );
                    }
                }
                else if (Questions[i].type == 2) {
                    console.log('填空题', Questions[i]);

                } else if (Questions[i].type == 3) {
                    var question = Questions[i].answer.split("@q=")[1].split("&opt=")[0];
                    var option = Questions[i].answer.split("&opt=")[1].split("&p=")[0];
                    var point = Questions[i].answer.split("&p=")[1];
                    console.log("type:3", 'q=', question, 'opt=', option, 'p=', point);
                    Questions[i].questions = [];
                    for (var j in question.split(',')) {
                        Questions[i].questions.push(question.split(',')[j]);
                    }
                    Questions[i].questions.pop();

                    Questions[i].options = [];
                    for (var k in option.split(',')) {
                        Questions[i].options.push(option.split(',')[k]);
                    }
                    Questions[i].options.pop();

                    Questions[i].points = [];
                    for (var j in point.split(';')) {
                        var p = [];
                        for (var k in point.split(';')[j].split(','))
                            p.push(point.split(';')[j].split(',')[k]);
                        p.pop()
                        Questions[i].points.push(p);
                    }
                    Questions[i].points.pop();
                }
            }
            return Questions;
        }

        //将options对象转换为字符串
        function zip(Questions) {
            for (var i in Questions) {
                Questions[i].seq = i;
                Questions[i].lb_id = lb.id;
                if(Questions[i].type != 2)
                Questions[i].answer = "";
                if (Questions[i].type == 0 || Questions[i].type == 1) {
                    for (var j in Questions[i].options) {
                        Questions[i].answer += "@q=" + Questions[i].options[j].option + "&p=" + Questions[i].options[j].point;
                    }
                }
                else if (Questions[i].type == 2) {
                    console.log('填空题',Questions[i])
                }
                else if (Questions[i].type == 3)
                //表格题
                {
                    Questions[i].answer += "@q=";
                    for (var j in Questions[i].questions)
                        Questions[i].answer += Questions[i].questions[j] + ',';

                    Questions[i].answer += "&opt=";
                    for (var k in Questions[i].options)
                        Questions[i].answer += Questions[i].options[k] + ',';

                    Questions[i].answer += "&p=";
                    for (var j in Questions[i].questions) {
                        for (var k in Questions[i].options)
                            Questions[i].answer += Questions[i].points[j][k] + ',';
                        Questions[i].answer += ";"
                    }
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

        function addOpt_q(index) {

            questions[index].options.push("");
            for (var i = 0; i < questions[index].questions.length; i++)
                questions[index].points[i].push(0);//每行增加一个
            loadHtml();
        }

        function addQue(index_que) {
            questions.push({
                question: "",
                quan: 1,
                options: [{option: "", point0: 0, point: 0}],
                type: 0
            });
            //console.log(questions);
            loadHtml();
        }

        function addQue_q(index) {

            questions[index].questions.push("");
            //console.log(questions[index]);
            var newArray = [];
            for (var i = 0; i < questions[index].options.length; i++)
                newArray.push(0);//每行增加一个
            questions[index].points.push(newArray);
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
                //console.log(JSON.stringify(questions));
                loadHtml();
            }
        }

        function changeQuestion(index_que, e) {
            questions[index_que].question = e.value;
            loadHtml();
        }

        function changeQue_q(index_que, index_opt, e) {
            questions[index_que].questions[index_opt] = e.value;
            loadHtml();
        }

        function changeOpt_q(index_que, index_ques, e) {
            questions[index_que].options[index_ques] = e.value;
            loadHtml();
        }

        function changeOption(index_que, index_opt, e) {
            console.log(e);
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

        function changePoint_q(index_que, index_que_q, index_opt_q, e) {
            //console.log(e)
            questions[index_que].points[index_que_q][index_opt_q] = e.value;
            loadHtml();
        }

        function changePoint_b(index_que, e) {

            questions[index_que].answer = e.value;
            console.log(questions,questions[index_que])
            loadHtml();
        }

        function changePoint0(index_que, index_opt, e) {
            console.log(e);
            var q = questions[index_que].options[index_opt];
            q.point0 = e.value;
            q.point = q.point0 * questions[index_que].quan;
            loadHtml();
        }

        function changequan(index_que, e) {
            questions[index_que].quan = e.value;
            var q = questions[index_que].options;
            for (var i = 0; i < q.length; i++)
                q[i].point = q[i].point0 * questions[index_que].quan;
            loadHtml();
        }

        function changeType(index_que, e) {
            questions[index_que].type = e.value;
            if (e.value == 2) {
                questions[index_que].question = ["这是问题"];
                questions[index_que].answer = 0;
            }
            else if (e.value == 3) {
                questions[index_que].questions = ["这是问题"];
                questions[index_que].options = ["这是选项"];
                questions[index_que].points = [[0]];
            }
            else {
                questions[index_que].options = [{option:"这是选项",point:0,point0:0}];
                questions[index_que].question="";
            }
            console.log(e.options[e.value], "问题：", questions)
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
            var s = JSON.stringify(lb);
            //console.log(s, deleted);
            lb.doctor_id ={{$admin->id}};
            lb.questions = q;
            lb.deleted = deleted;
            //获取tokenn
            var token = $("#token").children().val();
            lb._token = token;
            console.log(JSON.stringify(lb))
            //console.log("submitAll lb:" + JSON.stringify(lb));
            editLB("{{URL::asset('')}}", JSON.stringify(lb), function (ret, err) {
                //console.log(JSON.stringify(ret))

                alert("提交成功")
            })
        }

    </script>
@endsection