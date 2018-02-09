@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 答题记录</a></li>
                    <li class="active">评分</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="submitAll()">
                    保存评分
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box-body">
            <div id="token">{{csrf_field()}}</div>
            <div id="LBinfo"></div>
            <div id="message-content"></div>
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
                                    @{{?question.type=='2'}}
                                    @{{~question.questions :que:idx}}
                                    <span style="border: none;width: 60%">@{{=que}}</span>
                                    @{{?idx< question.result.length}}
                                    <span style="border-bottom: 1px solid black;font-weight: bold;background-color: yellow">
                                        @{{=question.result[idx]}}
                                    </span>
                                    @{{? }}
                                    @{{~ }}
                                    @{{?? }}
                                    <span style="border: none;width: 60%">@{{=question.question}}</span>
                                    @{{? }}
                                    <span class="pull-right">
                                    问题类型:
                                    <select disabled="true" name="type" style="border: none" value="@{{=question.type}}"
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
                                        <th class="col-md-1">序号</th>
                                        <th class="col-md-9">选项</th>
                                        <th class="col-md-1">分数</th>
                                    </tr>
                                    @{{~question.options :option:index_opt}}
                                    <tr style="@{{=option.style }}">

                                        <th>
                                            @{{=index_opt}}
                                        </th>

                                        <th>
                                            <span id="opt@{{=index_opt}}" style="border: none;width: 100%">
                                                @{{=option.option }}
                                            </span>

                                        </th>


                                        <th>
                                            <span id="point@{{=index_opt}}" style="border: none">
                                                @{{=option.point }}
                                            </span>
                                        </th>


                                    </tr>
                                    @{{~}}


                                </table>
                                @{{?? question.type=='2'}}
                                <span>分数最大值:@{{=question.answer }}</span>
                                @{{?? question.type=='3'}}
                                <table class="table table-bordered">
                                    <tr class="margin-top-10 grey-bg">
                                        <th style="width: 60px">问题</th>
                                        @{{~question.options:val_opt:index_q_opt1 }}
                                        <th style="width: 60px">
                                            <span style="width: 60px">@{{= val_opt}}</span>
                                        </th>
                                        @{{~ }}

                                    </tr>
                                    @{{~question.questions:val_que:index_q_que }}
                                    <tr>
                                        <th>
                                            <span style="width: 120px">@{{=val_que}}</span>
                                        </th>
                                        @{{~question.options:val_opt:index_q_opt }}
                                        <th style="@{{?question.result[index_q_que]==index_q_opt}}background-color:yellow;@{{? }}">
                                            {{--@{{= question.points[index_q_que][index_q_opt] }}--}}
                                            <span style="width: 60px">@{{= question.points[index_q_que][index_q_opt] }}</span>
                                        </th>
                                        @{{~ }}
                                    </tr>

                                    @{{~ }}

                                </table>
                                @{{?}}

                            </div>

                            <div class="col-md-12">
                                {{--//分数可以修改--}}
                                得分:
                                <input value="@{{=question.score}}" onchange="changeScore('@{{=index_que}}',this)"
                                       type="number" min="0">

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
        var result;
        var lb;
        var questions = [];
        var deleted = [];
        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            var answer_id = getQueryString("id");
            if (judgeIsNullStr(answer_id)) {
                //没有id则报错
                alert("量表答题记录id为空，请联系管理员处理");
                return;
            } else {
                var param1 = {
                    id: answer_id
                }
                getLBAnswerByID("{{URL::asset('')}}", param1, function (ret, err) {
                    if (ret.result == true) {
                        result = ret.ret;
                        console.log("result:", result);
                        var lb_id = result.lb_id;
                        //从url中获取id
                        if (judgeIsNullStr(lb_id)) {
                            //没有id则报错
                            alert("量表id为空，请联系管理员处理");
                            return;
                        } else {
                            var param = {
                                id: lb_id,
                            }
                            getLBById("{{URL::asset('')}}", param, function (ret, err) {
                                //提示保存成功
                                if (ret.result == true) {
                                    lb = ret.ret;
                                    questions = unzip(lb.questions, result.result);
                                    loadHtml();
                                }
                                else {
                                    alert(ret.ret);
                                }
                            })
                        }
                    } else {
                        alert(ret.ret);
                    }

                })
            }

        });

        //处理接收到的问题数据,将answer字符串转化为options对象
        function unzip(Questions, Result) {
            var R = Result.split('@a=');
            R.shift();
            console.log("results:", R);
            for (var i in Questions) {
                console.log(Questions[i]);
                if (Questions[i].type == 0 || Questions[i].type == 1) {
                    Questions[i].options = [];
                    var opts = Questions[i].answer.split("@q=");
                    Questions[i].quan = 1;
                    for (var j = 1; j < opts.length; j++) {
                        Questions[i].options.push({
                                style: "",
                                option: opts[j].split('&p=')[0],
                                point: opts[j].split('&p=')[1],
                                point0: opts[j].split('&p=')[1],
                            }
                        );
                    }

                    if (Questions[i].type == 0) {
                        console.log("分数", Questions[i].type, Questions[i].options, R[i]);
                        Questions[i].result = R[i];
                        Questions[i].score = 0;
                        Questions[i].score += parseInt(Questions[i].options[R[i]].point);
                        Questions[i].options[R[i]].style = "background-color:yellow;"
                    }
                    if (Questions[i].type == 1) {
                        var rrrr = R[i].split(',');
                        Questions[i].result = R[i].split(',');
                        Questions[i].score = 0;
                        for (var x in rrrr) {
                            console.log("分数", Questions[i].type, Questions[i].options, rrrr);
                            Questions[i].score += parseInt(Questions[i].options[rrrr[x]].point);
                            Questions[i].options[rrrr[x]].style = "background-color:yellow;"
                        }
                    }
                }
                else if (Questions[i].type == 2) {
                    Questions[i].score = 0;
                    Questions[i].questions = Questions[i].question.split("()");
                    var results = R[i].split('&bk=');
                    results.shift();
                    Questions[i].result = results;
                    console.log('填空题', Questions[i]);
                } else if (Questions[i].type == 3) {
                    Questions[i].score = 0;
                    Questions[i].result = R[i].split(',');
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
                    var rrrr = R[i].split(',');
                    rrrr.pop();
                    console.log("结果", rrrr)
                    for (var x in rrrr) {
                        Questions[i].score += parseInt(Questions[i].points[x][rrrr[x]])
                    }
                }
            }
            return Questions;
        }

        //将options对象转换为字符串
        function zip(Questions) {
            for (var i in Questions) {
                Questions[i].seq = i;
                Questions[i].lb_id = lb.id;
                if (Questions[i].type != 2)
                    Questions[i].answer = "";
                if (Questions[i].type == 0 || Questions[i].type == 1) {
                    for (var j in Questions[i].options) {
                        Questions[i].answer += "@q=" + Questions[i].options[j].option + "&p=" + Questions[i].options[j].point;
                    }
                }
                else if (Questions[i].type == 2) {
                    console.log('填空题', Questions[i])
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
            console.log(questions, questions[index_que])
            loadHtml();
        }

        function changePoint0(index_que, index_opt, e) {
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
            if (e.value == 32) {
                questions[index_que].questions = ["这是问题"];
                questions[index_que].answer = 0;
            }
            if (e.value == 3) {
                questions[index_que].questions = ["这是问题"];
                questions[index_que].options = ["这是选项"];
                questions[index_que].points = [[0]];
            }
            //console.log(e.options[e.value], "问题：", questions)
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

        function changeScore(index, e) {
            questions[index].score = e.value;
        }

        function submitAll() {
            var param = {};
            var q = zip(questions);
            var score = 0;
            for (var i in q) {
                score += parseInt(q[i].score);
            }
            param.score = score;
            //console.log(s, deleted);
            param.doctor_id ={{$admin->id}};
            param.id = result.id;
            //获取tokenn
            var token = $("#token").children().val();
            param._token = token;
            console.log(JSON.stringify(param))
            //console.log("submitAll lb:" + JSON.stringify(lb));
            checkAnswer("{{URL::asset('')}}", JSON.stringify(param), function (ret, err) {
                console.log(JSON.stringify(ret));
                alert("提交成功");
            })
        }

    </script>
@endsection