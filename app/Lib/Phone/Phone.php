<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16
 * Time: 18:08
 */

namespace App\Lib\Phone;


use App\Lib\Phone\Drivers\ChinaSmsDriver;

class Phone  implements PhoneDriverInterface {


    protected $driver;

    protected $driver_alies = [
        'china_sms' => ChinaSmsDriver::class,

    ];

    public function __construct($phone_driver = null)
    {
        $phone_driver = $phone_driver ?: config("phone.default");

        $config =  config("phone.driver.".$phone_driver);


        $driver_class = $this->driver_alies[$config['driver']];
        $this->driver = new $driver_class($config);
    }


    public static function getDriver($phone_driver = null) : PhoneDriverInterface
    {

        return (new self($phone_driver))->driver;

    }

    public function sendMessage(string $phone, string $msg)
    {
      return $this->driver->sendMessage($phone, $msg);
    }

    public function batchSendMessage(array $phones, string $msg)
    {
       return $this->driver->sendMessage($phones, $msg);

    }
}
