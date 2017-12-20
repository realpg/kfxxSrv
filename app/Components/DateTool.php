<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/12/4
 * Time: 9:23
 */

namespace App\Components;


class DateTool
{

    /*
     * 格式化日期函数
     *
     * By TerryQi
     *
     * 2017-12-07
     *
     * style具体查看代码中对应的日期处理函数
     *
     */
    public static function formateData($date_str, $style)
    {
        switch ($style) {
            case 0:
                return self::getYMDChi($date_str);
            case 1:
                return self::getYMDHSChi($date_str);
        }
        return $date_str;
    }

    /*
     * 将2017-11-27 00:00:00转换为2017年11月27日
     *
     * By TerryQi
     *
     * 2017-12-04
     *
     */
    public static function getYMDChi($date_str)
    {
        $date_arr = explode(' ', $date_str);
        $date_obj_arr = explode('-', $date_arr[0]);
        return $date_obj_arr[0] . "年" . $date_obj_arr[1] . "月" . $date_obj_arr[2] . "日";
    }

    /*
     * 将2017-11-27 00:00:00转换为2017-11-27
     *
     * By TerryQi
     *
     * 2017-12-04
     */
    public static function getYMD($date_str)
    {
        $date_arr = explode(' ', $date_str);
        return $date_arr[0];
    }

    /*
     * 将2017-11-27 00:00:00转换为2017年11月27日 12:12
     *
     * By TerryQi
     *
     * 2017-12-06
     *
     */
    public static function getYMDHSChi($date_str)
    {
        $date_arr = explode(' ', $date_str);
        $date_obj_arr = explode('-', $date_arr[0]);
        $time_obj_arr = explode(':', $date_arr[1]);
        return $date_obj_arr[0] . "年" . $date_obj_arr[1] . "月" . $date_obj_arr[2] . "日" . " " . $time_obj_arr[0] . ":" . $time_obj_arr[1];
    }


}