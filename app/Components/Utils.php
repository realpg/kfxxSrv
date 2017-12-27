<?php
/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/12/4
 * Time: 9:23
 */

namespace App\Components;


class Utils
{
    const PAGE_SIZE = 8;


    /*
     * 判断一个对象是不是空
     *
     * By TerryQi
     *
     * 2017-12-23
     *
     */
    public static function isObjNull($obj)
    {
        if ($obj == null || $obj == "") {
            return true;
        }
        return false;
    }

    /*
     * 判断一个宣教数据集的id是否在数组中的id，即用于新建宣教步骤时，判断是否需要删除该步骤
     *
     * By TerryQi
     *
     * 2017-12-23
     *
     */
    public static function isIdInArray($id, $arrs)
    {
        foreach ($arrs as $arr) {
            if (array_key_exists('id', $arr) && $arr['id'] == $id) {
                return true;
            }
        }
        return false;
    }


    /*
     * 根据生日计算年龄
     *
     * By TerryQi
     *
     * 2017-12-26
     *
     */
    public static function getAge($birthday)
    {
        $age = 0;
        if (!empty($birthday)) {
            $age = strtotime($birthday);
            if ($age === false) {
                return 0;
            }
            list($y1, $m1, $d1) = explode("-", date("Y-m-d", $age));
            list($y2, $m2, $d2) = explode("-", date("Y-m-d"), time());
            $age = $y2 - $y1;
            if ((int)($m2 . $d2) < (int)($m1 . $d1)) {
                $age -= 1;
            }
        }
        return $age;
    }
}