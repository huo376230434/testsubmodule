<?php
/**
 * Created by PhpStorm.
 * User: huo
 * Date: 18-5-16
 * Time: 下午12:01
 */
namespace App\Lib\Common\ThirdUtils;


use Endroid\QrCode\QrCode;

class PutQrcodeToImg{


    public static function putQrcode($code,$params =[])
    {
        $size = (isset($params["size"]) && $params["size"]) ? $params["size"] : 200;
        $padding = (isset($params["padding"]) && $params["padding"]) ? $params["padding"] : 20;

        $qrCode = new QrCode($code);
        //设置前景色
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' =>0, 'a' => 0]);
        //设置背景色
        $qrCode->setBackgroundColor(['r' => 250, 'g' => 255, 'b' => 255, 'a' => 10]);
        //设置二维码大小

        $qrCode->setSize($size);

        $qrCode->setPadding($padding);
        //添加logo
        if(isset($params['logo_path'])  && $params['logo_path'] ){
            $qrCode->setLogo($params['logo_path']);
            //设置logo大小

            $qrCode->setLogoSize(40);
        }
        if(isset($params['label']) && $params['label']) {

            $qrCode->setLabel($params['label']);
            $qrCode->setLabelFontSize(14);
            $qrCode->setLabelHalign(100);
        }

        if(isset($params["has_border"]) && $params["has_border"]){
            //绘制二维码边框
            $qrCode->setDrawBorder($params["has_border"]);
        }

        if(isset($params["save_img_path"]) && $params["save_img_path"]){
//生成图片
            $qrCode->save($params["save_img_path"]);
        }else{

            //获取二维码数据
            $img_data= $qrCode->getDataUri();

            return $img_data;
        }

    }


}
