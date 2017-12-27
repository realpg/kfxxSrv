@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">患者管理</li>
                    <li class="active">康复计划管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +保存患者康复计划
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="white-bg">
            <div style="padding: 15px;">
                <div class="font-size-16 text-info">
                    患者病历详情
                    <span class="pull-right margin-right-10 font-size-12" style="cursor: pointer;"
                          onclick="clickShowCaseInfo();">
                                <i class="fa fa-angle-down opt-btn-i-size"></i>
                                展开
                            </span>
                </div>
                <div id="userCaseInfo_div" style="display: none;">
                    <div class="margin-top-10 font-size-14 grey-bg">
                        <div style="padding: 10px;">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <td rowspan="2">
                                        <img src="{{ $user->avatar ? $user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             style="width: 60px;height: 60px;">
                                    </td>
                                    <td>患者</td>
                                    <td>{{$user->real_name}}</td>
                                    <td>性别</td>
                                    <td>
                                        @if($user->gender=="0")
                                            保密
                                        @endif
                                        @if($user->gender=="1")
                                            男
                                        @endif
                                        @if($user->gender=="2")
                                            女
                                        @endif
                                    </td>
                                    <td>联系方式</td>
                                    <td>{{$user->phonenum}}</td>
                                </tr>
                                <tr>
                                    <td>出生日期</td>
                                    <td>{{$user->birthday}}</td>
                                    <td>年龄</td>
                                    <td>{{$user->age}}岁</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="margin-top-10 font-size-14 grey-bg">
                        <div style="padding: 10px;">
                            <table class="table table-bordered table-hover">
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>关联模板</td>
                                    <td>{{$userCase->kfmb->name}}</td>
                                    <td>手术时间</td>
                                    <td>{{$userCase->ss_time}}</td>
                                    <td>首次弯腿时间</td>
                                    <td>{{$userCase->wt_time}}</td>
                                </tr>
                                <tr>
                                    <td>主治医师</td>
                                    <td class="">{{$userCase->zz_doctor->name}}</td>
                                    <td>康复医师</td>
                                    <td class="">{{$userCase->kf_doctor->name}}</td>
                                    <td>病历建立时间</td>
                                    <td>{{$userCase->created_at}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <div id="userCase_desc_div">
                                {{$userCase->desc}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="margin-top-10  font-size-14">
                    点击查看：<a href="#"><span
                                class="text-aqua">患者执行计划详情<i
                                    class="fa fa-link margin-left-5"></i></span></a>
                </div>
            </div>
        </div>

        {{--计划部分--}}



    </section>


@endsection


@section('script')
    <script type="application/javascript">


        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        function clickShowCaseInfo() {
            $("#userCaseInfo_div").toggle();
        }


    </script>
@endsection