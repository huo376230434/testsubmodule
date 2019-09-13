<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16
 * Time: 18:08
 */

namespace App\Lib\Phone;


interface PhoneDriverInterface {



    public  function sendMessage(string $phone,string $msg);


    public  function batchSendMessage(array $phones,string $msg);




}
