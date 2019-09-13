<?php
namespace App\Lib\Common\ThirdUtils;

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 20:03
 */
class RarCli{


    public static  function unZip($souce_path,$des_dir)
    {
        if (!is_dir($des_dir)) {
            mkdir($des_dir);
        }
        $command = "7Z x " . $souce_path . "  -o" . $des_dir;
//        dump($command);
        $res = shell_exec($command);
        dump($res);
    }
}
