<?php

namespace App\Lib\Common\Utils;
use Ramsey\Uuid\Uuid;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/17
 * Time: 16:35
 */

class SignUtil{


    /**格式化公钥
     * $pubKey PKCS#1格式的公钥串
     * return pem格式公钥， 可以保存为.pem文件
     * @param $pubKey
     * @return string
     */
    public  static  function formatPubKey($pubKey)
    {
        $fKey = "-----BEGIN PUBLIC KEY-----\n";
        $len = strlen($pubKey);
        for ($i = 0; $i < $len;) {
            $fKey = $fKey . substr($pubKey, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "-----END PUBLIC KEY-----";
        return $fKey;
    }


    /**格式化公钥
     * $priKey PKCS#1格式的私钥串
     * return pem格式私钥， 可以保存为.pem文件
     * @param $priKey
     * @return string
     */
    public  static function formatPriKey($priKey)
    {
        $fKey = "-----BEGIN RSA PRIVATE KEY-----\n";
        $len = strlen($priKey);
        for ($i = 0; $i < $len;) {
            $fKey = $fKey . substr($priKey, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "-----END RSA PRIVATE KEY-----";
        return $fKey;
    }


    /**RSA签名
     * @param $data待签名数据
     * @param $priKey商户私钥
     *
     * 使用MD5摘要算法
     * 最后的签名，需要用base64编码
     * @return string 签名用商户私钥
     */
    public static function rsaSign($data, $priKey)
    {

        //转换为openssl密钥
        $res = openssl_get_privatekey($priKey);

        //调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res, OPENSSL_ALGO_MD5);

        //释放资源
        openssl_free_key($res);

        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**RSA验签
     * @param $data待签名数据
     * @param $sign需要验签的签名
     * @param $pubKey公钥
     * @return bool 验签是否通过bool值
     */
   public static function rsaVerify($data, $sign, $pubKey)
    {
        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);

        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_MD5);

        //释放资源
        openssl_free_key($res);

        //返回资源是否成功
        return $result;
    }





}
