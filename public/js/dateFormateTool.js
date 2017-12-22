/**
 * Created by HappyQi on 2017/12/1.
 *
 * 该JS库主要用于时间格式处理
 *
 * 如有问题请联系TerryQi
 *
 * CopyRight @ 沈阳艺萨优先公司&爱循环团队
 *
 */

//日期格式化///////////////////////////////////////////////////////////////////////////////////

/*
 * 格式化工具
 */
Date.prototype.format = function (format) {
    var o = {
        "M+": this.getMonth() + 1, //month
        "d+": this.getDate(), //day
        "h+": this.getHours(), //hour
        "m+": this.getMinutes(), //minute
        "s+": this.getSeconds(), //second
        "q+": Math.floor((this.getMonth() + 3) / 3), //quarter
        "S": this.getMilliseconds() //millisecond
    }

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }

    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}

/*
 * 获取当前时间，格式为2017-12-1 13:24:00
 */
function getCurrentTime() {
    var curr = new Date();
    return curr.format("yyyy-MM-dd hh:mm:ss");
}

// 时间格式转换
/*
 * 将2017-12-1 13:24:00转换为2017年12月1日
 *
 */
function convertDateToChinese(date_str) {
    if (judgeIsAnyNullStr(date_str)) {
        return "";
    }
    var date_arr = date_str.split(' ');
    var date_str_arr = date_arr[0].split("-");
    return date_str_arr[0] + "年" + date_str_arr[1] + "月" + date_str_arr[2] + "日";
}