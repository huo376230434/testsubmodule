<?php
namespace App\Lib\Common\ThirdUtils;
use Intervention\Image\ImageManagerStatic ;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9
 * Time: 18:33
 */
class ImageEditer {


    public static function reSize($img_path, $width, $dest_path=null,$height=null)
    {

       $img = ImageManagerStatic::make($img_path);
        if ($height) {
            $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
            });
        }else{
            $img->resize($width,$width);
        }


        return  $img->save($dest_path);
    }





}
