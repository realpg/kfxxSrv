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
            <button type="button" class="btn btn-primary" onclick="submitAll()">
                保存量表
            </button>
        </div>
    </section>
    <script id="LBinfo-content-template" type="text/x-dot-template">


        <div class="form-group">
            <label for="seq" class="col-sm-2 control-label">标题*:</label>

            <div class="col-sm-10">
                <input onchange="changeName(this)" type="text" class="form-control"
                       placeholder="请输入" value=@{{=it.name}}>
            </div>
        </div>
        <hr/>
        <div class="form-group">
            <label for="seq" class="col-sm-2 control-label">描述:</label>

            <div class="col-sm-10">
                     <textarea onchange="changeDesc(this)" type="text" placeholder="请输入描述" rows="4"
                               style="resize: none;width: 100%"
                     >@{{=it.desc}}</textarea>
            </div>
        </div>


    </script>


    <script id="message-content-template" type="text/x-dot-template">
        @{{~it :question:index_que}}
        <hr/>
        <div class="form-group">
            <label for="seq" class="col-sm-2 control-label">问题*:</label>

            <div class="col-sm-9">
                <input onchange="changeQuestion('@{{=index_que}}',this)" type="text"
                       class="form-control" placeholder="请输入问题"
                       value=@{{=question.question}}>
            </div>
            <div class="col-sm-1">
            <img src="{{URL::asset('/img/delete_icon.png')}}" class=" opt-btn-size"
                 onclick="delQue('@{{=index_que}}');">
            </div>
        </div>
        @{{~question.options :option:index_opt}}
        <hr/>
        <div class="form-group">
            <label for="opt@{{=index_opt}}" class="col-sm-2 control-label text-right">选项@{{=index_opt}}*</label>
            <div class="col-sm-5">
                <input id="opt@{{=index_opt}}" onchange="changeOption('@{{=index_que}}',
                '@{{=index_opt}}',this)" type="text" placeholder="请输入选项"
                       style="width: 100%" value=@{{=option.option }}>
            </div>
            <label for="point@{{=index_opt}}" class="col-sm-1 control-label text-right">分数*</label>
            <div class="col-sm-3">
                <input id="point@{{=index_opt}}" onchange="changePoint('@{{=index_que}}','@{{=index_opt}}',this)"
                       type="number"
                       class="form-control" value=@{{=option.point }}>
            </div>
            <button class="col-sm-1 btn btn-danger"
                 onclick="delOpt('@{{=index_que}}','@{{=index_opt}}');">删除选项
            </button>
        </div>
        @{{~}}
        <hr/>
        <div style="float: none;display: block;margin-left: auto;margin-right: auto;width: 30%">
            <button onclick="addOpt('@{{=index_que}}')" class="btn btn-success">
                添加问题
            </button>
        </div>
        <hr/>

        @{{~}}
        <div>
            <button onclick="addQue()" class="btn btn-success">
                addQue
            </button>
        </div>

    </script>


    <!--新建编辑宣教步骤对话框-->
    <div class="modal fade modal-margin-top-m" id="editJHModal" tabindex="-1" role="dialog">


    </div>

    <script id="editJHModal-content-template" type="text/x-dot-template">

    </script>


    <!--数据采集模板-->
    <div class="modal fade modal-margin-top-m" id="editSJModal" tabindex="-1" role="dialog">

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
        var deleted=[];
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
                for (var j=1;j<opts.length ;j++) {
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
                Questions[i].lb_id=lb.id;
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

        function addQue() {
            questions.push({
                question: "",
                options: [{option: "", point: 0}]
            });
            loadHtml();
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
            if(questions[index_que].id)
            deleted.push(questions[index_que].id);
            questions.splice(index_que,1);
            loadHtml();
        }
        function delOpt(index_que,index_opt) {
            questions[index_que].options.splice(index_opt,1);
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
            lb.doctor_id={{$admin->id}};
            var q = zip(questions);
            lb.questions=q;
            var s = JSON.stringify(lb)

            console.log(s,deleted);
            lb.doctor_id ={{$admin->id}};
            lb.questions = q;
            lb.deleted=deleted;
            //获取tokenn
            var token=$("#token").children().val();
            alert(token);
            lb._token=token;
            editLB("{{URL::asset('')}}", JSON.stringify(lb), function (ret, err) {

            })
        }

    </script>
@endsection