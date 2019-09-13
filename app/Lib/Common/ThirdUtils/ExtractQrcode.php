<?php

namespace App\Lib\Common\ThirdUtils;

use PHPZxing\PHPZxingDecoder;
use PHPZxing\ZxingImage;
use Zxing\QrReader;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/20
 * Time: 10:43
 */
class ExtractQrcode{



    public static function getQrcode($qrcode_img_path){


        try {
            $qrcode = new QrReader($qrcode_img_path);
            $text = $qrcode->text(); //return decoded text
            return $text;
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            return false;
        }


//
//
//        $config = array(
//            'try_harder' => true, // 当不知道二维码的位置是设置为true
//            'multiple_bar_codes' => true, // 当要识别多张二维码是设置为true
////            'crop' => '100,200,300,300', // 设置二维码的大概位置
//        );
//        $decoder        = new PHPZxingDecoder($config);
//        $decoder->setJavaPath( config('admin.java_path'));  //设置jdk的安装路径，该扩展是居于java的，所以需要jdk。如果设置了jdk的环境变量则无需设置
////dd(config('admin.java_path'));
//        $decodedOriginData = $decoder->decode($qrcode_img_path);
//      if( $decodedOriginData){
//          $decodedData = current($decodedOriginData); // 路径需要时绝对路径或相对路径，不能是url
//      }  else{
//          throw new \Exception($qrcode_img_path."无法访问不到");
//      }
//        /**
//         *返回的对象类型
//         * 识别成功时返回ZxingImage对象
//         * 图片中没有识别的二维码时返回ZxingBarNotFound对象
//        /**/
//        if($decodedData instanceof ZxingImage){
//           return  $decodedData->getImageValue();  // 二维码的内容
//        }else{
//            return false;
//        }
//

    }


}
