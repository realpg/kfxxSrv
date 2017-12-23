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

}