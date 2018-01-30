// 接口部分
//基本的ajax访问后端接口类
function ajaxRequest(url, param, method, callBack) {
    console.log("url:" + url + " method:" + method + " param:" + JSON.stringify(param));
    $.ajax({
        type: method,  //提交方式
        url: url,//路径
        data: param,//数据，这里使用的是Json格式进行传输
        contentType: "application/json", //必须有
        dataType: "json",
        success: function (ret) {//返回数据根据结果进行相应的处理
            console.log("ret:" + JSON.stringify(ret));
            callBack(ret)
        },
        error: function (err) {
            console.log(JSON.stringify(err));
            console.log("responseText:" + err.responseText);
            callBack(err)
        }
    });
}

//根据id获取轮播图信息
function changePassword(url, param, callBack) {
    ajaxRequest( url + "admin/changePassword?"+param, null, "POST", callBack);
}
//根据id获取轮播图信息
function getADById(url, param, callBack) {
    ajaxRequest(url + "api/ad/getById", param, "GET", callBack);
}
//根据id获取轮播图信息
function getLBById(url, param, callBack) {
    ajaxRequest(url + "api/lb/getQuestionsById", param, "GET", callBack);
}
//根据记录id获取量表答题记录
function getLBAnswerByID(url,param,callBack) {
    ajaxRequest(url + "api/lb/getAnswerHistoryByID", param, "GET", callBack);
}
//评分
function checkAnswer(url, param, callBack) {
    ajaxRequest(url + "admin/lb/checkAnswer", param, "POST", callBack);
}
//根据id获取康复模板信息
function getKFMBById(url, param, callBack) {
    ajaxRequest(url + "api/kfmb/getKFMBById", param, "GET", callBack);
}

//根据id获取患者信息
function getUserById(url, param, callBack) {
    ajaxRequest(url + "api/user/getById", param, "GET", callBack);
}

//根据id获取患者病例信息
function getUserCaseById(url, param, callBack) {
    ajaxRequest(url + "api/userCase/getUserCaseById", param, "GET", callBack);
}

//编辑患者病例
function editUserCaseKFJH(url, param, callBack) {
    ajaxRequest(url + "admin/user/editUserCaseKFJH", param, "POST", callBack);
}

//根据id获取医生信息
function getDoctorById(url, param, callBack) {
    ajaxRequest(url + "api/doctor/getById", param, "GET", callBack);
}

//根据id获取数据项
function getSJXById(url, param, callBack) {
    ajaxRequest(url + "admin/sjx/edit", param, "GET", callBack);
}

//获取全部生效宣教信息
function getAllXJs(url, param, callBack) {
    ajaxRequest(url + "api/xj/getAll", param, "GET", callBack);
}

//获取宣教类别信息
function getXJTypeById(url, param, callBack) {
    ajaxRequest(url + "api/xj/getXJTypeById", param, "GET", callBack);
}

//根据id获取宣教
function getXJInfoById(url, param, callBack) {
    ajaxRequest(url + "api/xj/getXJInfoById", param, "get", callBack);
}

//根据id获取量表
function getLBById(url, param, callBack) {
    ajaxRequest(url + "api/lb/getById", param, "get", callBack);
}

function editLB(url, param, callBack) {
    ajaxRequest(url + "admin/lb/editLB", param, "post", callBack);
}

//编辑宣教
function editXJ(url, param, callBack) {
    ajaxRequest(url + "admin/xj/editXJ", param, "post", callBack);
}

//编辑康复模板
function editKFMB(url, param, callBack) {
    ajaxRequest(url + "admin/kfmb/editKFMB", param, "post", callBack);
}

//根据id获取患处信息
function getHPosById(url, param, callBack) {
    ajaxRequest(url + "api/hPos/getById", param, "get", callBack);
}

//编辑患处信息
function editHPos(url, param, callBack) {
    ajaxRequest(url + "admin/hPos/edit", param, "post", callBack);
}

//手术信息
function getSurgeryById(url, param, callBack) {
    ajaxRequest(url + "admin/surgery/edit", param, "get", callBack);
}
//手术信息
function getCJSJDetailById(url, param, callBack) {
    ajaxRequest(url + "admin/user/getCJSJDetailById", param, "get", callBack);
}

//测试接口
function test(url, param, callBack) {
    ajaxRequest(url + "api/test", param, "post", callBack);
}


/*
 * 校验手机号js
 *
 * By TerryQi
 */

function isPoneAvailable(phone_num) {
    var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
    if (!myreg.test(phone_num)) {
        return false;
    } else {
        return true;
    }
}

// 判断参数是否为空
function judgeIsNullStr(val) {
    if (val == null || val == "" || val == undefined || val == "未设置") {
        return true
    }
    return false
}

// 判断参数是否为空
function judgeIsAnyNullStr() {
    if (arguments.length > 0) {
        for (var i = 0; i < arguments.length; i++) {
            if (!isArray(arguments[i])) {
                if (arguments[i] == null || arguments[i] == "" || arguments[i] == undefined || arguments[i] == "未设置" || arguments[i] == "undefined") {
                    return true
                }
            }
        }
    }
    return false
}

// 判断数组时候为空, 服务于 judgeIsAnyNullStr 方法
function isArray(object) {
    return Object.prototype.toString.call(object) == '[object Array]';
}


// 七牛云图片裁剪
function qiniuUrlTool(img_url, type) {
    //如果不是七牛的头像，则直接返回图片
    //consoledebug.log("img_url:" + img_url + " indexOf('isart.me'):" + img_url.indexOf('isart.me'));
    if (img_url.indexOf('7xku37.com') < 0 && img_url.indexOf('isart.me') < 0) {
        return img_url;
    }
    //七牛链接
    var qn_img_url;
    const size_w_500_h_200 = '?imageView2/2/w/500/h/200/interlace/1/q/75|imageslim'
    const size_w_200_h_200 = '?imageView2/2/w/200/h/200/interlace/1/q/75|imageslim'
    const size_w_500_h_300 = '?imageView2/2/w/500/h/300/interlace/1/q/75|imageslim'
    const size_w_500_h_250 = '?imageView2/2/w/500/h/250/interlace/1/q/75|imageslim'

    const size_w_500 = '?imageView1/1/w/500/interlace/1/q/75'

    //除去参数
    if (img_url.indexOf("?") >= 0) {
        img_url = img_url.split('?')[0]
    }
    //封装七牛链接
    switch (type) {
        case "ad":  //广告图片
            qn_img_url = img_url + size_w_500_h_300
            break
        case "folder_list":  //作品列表图片样式
            qn_img_url = img_url + size_w_500_h_200
            break
        case  'head_icon':      //头像信息
            qn_img_url = img_url + size_w_200_h_200
            break
        case  'work_detail':      //作品详情的图片信息
            qn_img_url = img_url + size_w_500
            break
        default:
            qn_img_url = img_url
            break
    }
    return qn_img_url
}


// 文字转html，主要是进行换行转换
function Text2Html(str) {
    if (str == null) {
        return "";
    } else if (str.length == 0) {
        return "";
    }
    str = str.replace(/\r\n/g, "<br>")
    str = str.replace(/\n/g, "<br>");
    return str;
}

//null变为空str
function nullToEmptyStr(str) {
    if (judgeIsNullStr(str)) {
        str = "";
    }
    return str;
}


/*
 * 用于对象克隆
 *
 * obj 对象，返回克隆对象
 *
 */
function clone(obj) {
    // Handle the 3 simple types, and null or undefined
    if (null == obj || "object" != typeof obj) return obj;

    // Handle Date
    if (obj instanceof Date) {
        var copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }

    // Handle Array
    if (obj instanceof Array) {
        var copy = [];
        for (var i = 0, len = obj.length; i < len; ++i) {
            copy[i] = clone(obj[i]);
        }
        return copy;
    }

    // Handle Object
    if (obj instanceof Object) {
        var copy = {};
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
        }
        return copy;
    }

    throw new Error("Unable to copy obj! Its type isn't supported.");
}


/*
 * 获取url中get的参数
 *
 * By TerryQi
 *
 * 2017-12-23
 *
 */
function getQueryString(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}


//获取时间基线类型的字符串
function getBtimeTypeStr(btime_type) {
    switch (btime_type) {
        case "0":
            return "手术后";
        case "1":
            return "首次弯腿后";
        case "2":
            return "指定日期";
    }
    return "";
}

//获取康复计划状态字符串
function getJHStatus(status) {
    switch (status) {
        case "0":
            return "计划执行";
        case "1":
            return "执行中";
        case "2":
            return "已执行";
    }
}

//获取时间基线单位
function getTimeUnitStr(unit) {
    switch (unit) {
        case "0":
            return "天";
        case "1":
            return "周";
        case "2":
            return "月";
    }
}
