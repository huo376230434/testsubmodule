<?php

namespace App\Lib\Common\CommonBase;
use Ramsey\Uuid\Uuid;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/17
 * Time: 16:35
 */

class ScatteredUtil{

//    金额float转int
    /**
     * @param $amount
     * @param int $times 倍数
     * @return int
     */
    public static function moneyFormat($amount, $times=100)
    {
        if(self::isWindows()){
            return (int)($amount*100);//在windows上无法用money_format函数
        }
        return (int)money_format("%i",(float)$amount*$times);

    }


    /**
     * 环境是否是window
     * @return bool
     */
    public static function isWindows()
    {
        return PHP_OS === "WINNT";

    }


    /**
     *  作用：产生随机字符串，不长于32位
     * @param $length
     * @return string
     */
    public static function createNoncestr($length)
    {

        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;

    }



    /**
     * 为选择项增加第一个默认的选择
     * @param $arr
     * @param string $word
     * @param int $key
     * @return array|null
     */
    public static function addTotalSelect($arr, $word = "全部", $key=0)
    {
        $arr = array_flip($arr);
        $arr = array_merge([$word => $key],$arr);
        $arr = array_flip($arr);
        return $arr;
    }


    /**
     * 连接参数字符串
     * @param $params
     * @param array $out_array
     * @return string
     */
    public static function concatParams($params, $out_array = []) {

        ksort($params);
        $pairs = array();
        foreach($params as $key=>$val) {
            if(in_array($key,$out_array)){
                continue;
            }
            array_push($pairs, $key . '=' . $val);
        }
        return join('&', $pairs);
    }



    /**
     * 参数数组拼接到url上
     * @param $url
     * @param $params
     * @return string
     */
    public static function buildGetParamsUrl($url, $params)
    {
        $new_url = $url . "?";
        $new_url .= self::concatParams($params);
        return $new_url;
    }


    /**
     * 获得一个类的所有公开方法
     * @param $class
     * @param bool $own_parents
     * @return array
     */
    public static function getPublicMethods($class, $own_parents=false)
    {
//        获得当前类的所有公开方法
        $array1 = get_class_methods($class);

        //        获得父类的所有公开方法
        $parent_class = get_parent_class($class);

        if ($parent_class && !$own_parents) {
//            如果有父类，则排除父类
            $array2 = get_class_methods($parent_class);

            $array3 = array_diff($array1, $array2);
        } else {
            $array3 = $array1;
        }
        return $array3;
    }


}
