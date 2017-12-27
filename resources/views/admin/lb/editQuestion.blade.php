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
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    保存量表
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="message-content">

        </div>
        <button onclick="submitAll()">
            提交
        </button>
    </section>


    <script id="message-content-template" type="text/x-dot-template">
        @{{~it :question:index_que}}
        <div>
            <input onchange="changeQuestion('@{{=index_que}}',this)" type="text"placeholder="请输入问题" value=@{{=question.question}}>
        </div>
        @{{~question.options :option:index_opt}}

        <div>@{{=index_opt}}
            ：<input onchange="changeOption('@{{=index_que}}','@{{=index_opt}}',this)" type="text"placeholder="请输入选项" value=@{{=option.option }}>
            分数：<input onchange="changePoint('@{{=index_que}}','@{{=index_opt}}',this)" type="number" id="point" value=@{{=option.point }}>
        </div>
        @{{~}}
        <button onclick="addOpt('@{{=index_que}}')">
            addOpt
        </button>
        @{{~}}
        <div>
        <button onclick="addQue()">
            addQue
        </button></div>

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
                         lb=ret.ret;
                         questions=unzip(lb.questions);
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
            for(var i in Questions){
                Questions[i].options=[];
                var opts=Questions[i].answer.split("@q=");
                for(var j in opts){
                    Questions[i].options.push({
                            option:opts[j].split('&p=')[0],
                            point:opts[j].split('&p=')[1]
                    }
                    );
                }
            }
            return Questions;
        }
        //将options对象转换为字符串
        function zip(Questions) {
            for(var i in Questions){
                Questions[i].answer="";
                for(var j in Questions[i].options) {
                Questions[i].answer+="@q="+Questions[i].options[j].option+"&p="+Questions[i].options[j].point;
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
        }
        function addOpt(index) {

            questions[index].options.push({option:"",point:0})
            loadHtml();
        }
        function addQue() {
            questions.push({
                question:"",
                options:[{option:"",point:0}]
            });
            loadHtml();
        }
        function changeQuestion(index_que,e) {
            questions[index_que].question=e.value;
            loadHtml();
        }
        function changeOption(index_que,index_opt,e) {

            questions[index_que].options[index_opt].option=e.value;
            loadHtml();
        }
        function changePoint(index_que,index_opt,e) {
            questions[index_que].options[index_opt].point=e.value;
            loadHtml();
        }

        function submitAll() {
            var q=zip(questions);
            var s=JSON.stringify(q)
            alert(s);
            console.log(s)
        }

    </script>
@endsection