<?php
/**
 * Created by IntelliJ IDEA.
 * User: huo
 * Date: 2018/11/25
 * Time: 下午4:21
 */

namespace App\Lib\Common\Dictionary;

class JsonResult {


    public static $success_code = "";
    public static $success_msg = "";
    public static $failed_code = "";
    public static $failed_msg = "";

    public static function success($msg="",$data = [])
    {
       $res = [
            'status' => "success",
            'msg' => $msg ?? "成功",
        ];

        if (!empty($data)) $res['data'] = $data;
        return $res;
    }


    public static function failed($msg = "",$data = [])
    {
        $res = [
        'status' => "failed",
        'msg' => $msg ?? "失败",
    ];

        if (!empty($data)) $res['data'] = $data;
        return $res;

    }



}
