<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16
 * Time: 18:08
 */

namespace App\Lib\Phone\Drivers;


use App\Lib\Phone\PhoneDriverInterface;

class ChinaSmsDriver implements PhoneDriverInterface {

    protected $sms_config = [];
    protected $url = '';
    public function __construct($config)
    {
        $this->sms_config = $config;
    }


    public function sendMessage( string $phone, string $msg)
    {
        $this->sms_config['m'] = $phone;
        $this->sms_config['c'] = $msg;
        $xml= $this->postSMS($this->sms_config['url'], $this->sms_config);
        info("china_sms_result:".$xml);

        $re=simplexml_load_string(utf8_encode($xml));
        if(trim($re['result'])==1){ //发送成功 ，返回企业编号，员工编号，发送编号，短信条数，单价，余额
            return [
                'status' => 'success'
            ];
        }

        return [
            'status' => 'fail',
            'msg' => $re
        ];
    }


    /**
     * @param array $phones
     * @param string $msg
     * @throws \Exception
     */
    public function batchSendMessage(array $phones, string $msg)
    {
        throw new \Exception( "batchSendMessage 方法还没有开发！");
        // TODO: Implement batchSendMessage() method.
    }

//    执行发送短信的方法
    protected function postSMS($url,$data=[])
    {
        $row = parse_url($url);
        $host = $row['host'];
        $port = isset($row['port']) ? $row['port']:80;
        $file = $row['path'];
        $post = '';

        foreach($data as $k => $v) {
        $post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
    }

        $post = substr( $post , 0 , -1 );
        $len = strlen($post);
        $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.0\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n",$receive);
            unset($receive[0]);
            return implode("",$receive);
        }
    }

}
