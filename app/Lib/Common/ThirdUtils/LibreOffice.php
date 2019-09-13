<?php
/**
 * Created by PhpStorm.
 * User: huo
 * Date: 18-5-16
 * Time: 下午12:01
 */
namespace App\Lib\Common\ThirdUtils;


use App\Lib\Common\CommonBase\ScatteredUtil;

class LibreOffice{

    public static $tips = <<<DDD
确认libreoffice软件是否安装;
并且已经加入环境变量
注意，linux 的启动是libreoffice 
windows的启动命令是soffice
DDD;


    public static function wordToPdf($word_path,$des_dir)
    {

        $command = self::command()." --headless --convert-to pdf:writer_pdf_Export $word_path --outdir $des_dir";
        shell_exec($command);

    }

    public static function command()
    {
        if (ScatteredUtil::isWindows()){
            return "soffice ";

        }else{
            return "libreoffice ";
        }

    }


    public static function hasInstalled()
    {
        if (ScatteredUtil::isWindows()) {
            $command = self::command()." --version";
            $res = shell_exec($command);
            return empty($res);//windows 只要没有任何显示，就说明已经安装成功了
        }else{
            $command =  self::command()." --version";
            $res = shell_exec($command);
            return starts_with($res,"LibreOffice") && str_contains($res, "Build:");
        }
    }

    /**
     * @throws \Exception
     */
    public static function hasInstalledOrException()
    {
        if (!self::hasInstalled()) {
            throw new \Exception("确认libreoffice软件是否安装");
        }
    }



}
