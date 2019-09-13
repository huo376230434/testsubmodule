<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16
 * Time: 18:08
 */

namespace App\Lib\Common\CommonBase;

class FormatConversion {

    /**
     * 转成json
     * @param $data
     * @return string
     */
    public static function jsonEncode($data)
    {
        return stripslashes(json_encode($data, JSON_UNESCAPED_UNICODE));

    }

    public static function jsonEncodePretty($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT);

    }

    /**
     * json解析成数组
     * @param $data
     * @return mixed
     */
    public static function jsonDecode($data)
    {
        return json_decode($data, true);//返回数组

    }


    /**
     * array转xml
     * @param $arr
     * @return string
     */
    public static function arrayToXml($data,$root = true)
    {
        $str="";
        if($root)$str .= "<xml>";
        foreach($data as $key => $val){
            if(is_array($val)){
                $child = self::arrayToXml($val, false);
                $str .= "<$key>$child</$key>";
            }else{
                $str.= "<$key><![CDATA[$val]]></$key>";
            }
        }

        if($root)$str .= "</xml>";
        return $str;
    }

    /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     */
    public static function xmlToArray($xml)
    {
        $xmlarray = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $xmlarray;
    }


    /**
     * 将unicode 码转为utf8中文
     * @param $str
     * @return string|string[]|null
     */
    public static function decodeUnicode($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
            function($matches){
                return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");
            }
            ,
            $str);
    }


}
