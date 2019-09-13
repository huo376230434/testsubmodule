<?php
namespace App\Lib\Common\CommonBase;

/**
 * Created by IntelliJ IDEA.
 * User: huo
 * Date: 2019/2/5
 * Time: 下午1:41
 */
class  RegPatterns{
    public static function publicFunction($method)
    {
        return "/public\s+function\s+" . $method . "\s*\([\w-@&\[\]=\'\",$\s]*\)/";


    }


    public static function publicStaticFunction($method)
    {
        return "/public\s+static\s+function\s+" . $method . "\s*\([\[\]\w-@,&=\'\",$\s]*\)/";


    }
}
