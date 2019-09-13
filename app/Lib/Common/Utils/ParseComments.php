<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16
 * Time: 18:08
 */

namespace App\Lib\Common\Utils;


class ParseComments {


    public static function handle($coments)
    {
        $arr = explode("\n", $coments);
        array_pop($arr);

        $arr = array_reverse($arr);
        $var_arr = [];
        $suffix = "";
        foreach ($arr as $item) {
            $item = trim($item);
            if (starts_with($item,'/*')) {
                //首行
                $item = substr($item, 2);
            }
            $item = ltrim($item,'*' );
            $item = trim($item);
            if (starts_with($item,'@' )) {
                //说明是变量

                $res = [];
//                dump($item);
                 preg_match("/@[\w_]+/", $item,$res);
                $var = $res[0];
                $value = substr($item, strlen($var));
                $value = trim($value);
                $var = ltrim($var,'@');
//                dump($var);

                $var_arr[$var] = $value . $suffix;
                $suffix = '';
            }else{
                //不是变量
                $suffix = $item . $suffix;
            }
        }


        //进行变量替换

        return self::replaceVar($var_arr);


    }


    protected static function replaceVar($var_arr)
    {
     $has_replaced = true;
        foreach ($var_arr as $key => $item) {
           $res = preg_replace_callback('/\$\{[\w_]+\}/',function($matches)use(&$var_arr,&$has_replaced){
                $var = substr($matches[0], 2, -1);
                if (array_key_exists($var, $var_arr)) {
//                    dump($var_arr[$var]);
                    $has_replaced = false;
                    return $var_arr[$var];
                }
//                dump($var);
            },$item);

//            dump($res);
            $var_arr[$key] = $res;
        }
        if ($has_replaced) {
            return $var_arr;

        }else{
            return self::replaceVar($var_arr);
        }


    }



}
