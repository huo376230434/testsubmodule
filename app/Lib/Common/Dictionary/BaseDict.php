<?php
/**
 * Created by IntelliJ IDEA.
 * User: huo
 * Date: 2018/11/25
 * Time: 下午4:21
 */

namespace App\Lib\Common\Dictionary;

class BaseDict {


    public static function resourceList()
    {

        return [
            ['type' => 'get' , 'uri' => 'index'],
            ['type' => 'get' , 'uri' => 'create'],
            ['type' => 'post' , 'uri' => 'store'],
            ['type' => 'get' , 'uri' => 'show'],
            ['type' => 'get' , 'uri' => 'edit'],
            ['type' => 'put' , 'uri' => 'update'],
            ['type' => 'delete' , 'uri' => 'destroy'],

        ];

    }


    public static function modules($module=null)
    {

        $arr = [
            "Admin" => [

            ],
            "Http" => [
                'alias' => "home"
            ]
        ];
        if ($module) {
            return $arr[$module];
        }else{
            return $arr;
        }

    }


}
