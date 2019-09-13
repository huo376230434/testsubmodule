<?php

namespace App\Lib\Common\Dictionary;

use App\Lib\Common\Dictionary\FakerTraits\FakerSentence;
use App\Lib\Common\Dictionary\FakerTraits\FakerTest;
use Faker\Factory;

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2019/2/28
 * Time: 11:38
 */

class Dwfaker {
use FakerSentence,FakerTest;
    protected $chinese_faker;
    protected $faker;
    public function __construct()
    {
        $this->chinese_faker = Factory::create("zh_CN");
        $this->faker = Factory::create();
    }

    public function number()
    {
        return rand(1, 999);

    }

    public function password()
    {
        return "123456ab";

    }


    public function realEmail()
    {
        $arr = [
         config('mail.test_mail_user')
        ];
        return array_random($arr);
    }

    public function loginAccount()
    {
        $arr = [
            "test",
            "worker",
            "faker",
            "account"
        ];
        return array_random($arr);
    }


    public function chineseName()
    {
        return $this->chinese_faker->name;

    }

    public function phone()
    {
        return $this->chinese_faker->phoneNumber;

    }

    public function email()
    {
        return $this->chinese_faker->safeEmail;

    }

    public function address()
    {
        return $this->chinese_faker->address;

    }

    public function imgurl()
    {
        return $this->chinese_faker->imageUrl;

    }


    public function bigImgUrl()
    {
        return $this->chinese_faker->imageUrl(1920, 1080);

    }

    public function thumbUrl()
    {
        return $this->chinese_faker->imageUrl(200, 200);

    }

    public function color()
    {
        return $this->chinese_faker->hexColor;

    }

    public function colorName()
    {
        return $this->faker->safeColorName;

    }

    public function bank()
    {
        return $this->chinese_faker->bank;

    }

    public function company()
    {
        return $this->chinese_faker->company;

    }

    public function sentence()
    {
//        return $this->chinese_faker->catchPhrase;
        return array_random($this->sentence_arr);
    }

    public function note()
    {
        $i=4;
        $str = '';
        while ($i) {
            $str .= $this->sentence()." ;";
            $i--;
        }
        $str = trim($str,';');
        $str .= '。';
        return $str;
    }

    public function word()
    {
        return $this->chinese_faker->word;
    }

    public function md5()
    {
        return $this->chinese_faker->md5;
    }

    public function html()
    {
        return $this->chinese_faker->randomHtml;
    }


}
