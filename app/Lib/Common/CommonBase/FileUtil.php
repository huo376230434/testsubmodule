<?php

namespace App\Lib\Common\CommonBase;


/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/17
 * Time: 16:35
 */

class FileUtil{


    /**
     * 取得所传文件夹下的所有文件名或属性
     * @param $path
     * @param bool $stretch
     * @return array
     */
    public static function allFileWithAttrs($path,  $stretch = false)
    {

        $arr = self::allFile($path,$stretch);

//            如果要拿到所有文件的属性
        foreach ($arr as $key => $value) {
            $temp_arr = [];

            $full_name = $path . "/" . $value;
            $temp_arr['id'] = $key+1;

            $temp_arr['name'] = $value;
            $temp_arr['filemtime'] =date("Y-m-d H:i:s", filemtime($full_name));
            $temp_arr['size'] =sprintf("%.2f",filesize($full_name) / (1024*1024)) ;
            $arr[$key] = $temp_arr;
        }


        return $arr;
    }


    public static function allFileWithoutDir($path, $stretch = false, $base_name = "")
    {
        $arr = self::allFile($path, $stretch, $base_name);
        foreach ($arr as $key => $item) {
            if (is_dir($path . $item)) {
                unset($arr[$key]);
            }

        }
        return $arr;

    }

    /**
     * 取得所传文件夹下的所有文件名
     * @param $path
     * @param bool $stretch
     * @param string $base_name //递归时子方法需要这个参数
     * @return array
     */
    public static function allFile($path, $stretch = false, $base_name = "")
    {
        $handler = opendir($path);//当前目录中的文件夹下的文件夹
        $arr = [];
        while( ($filename = readdir($handler)) !== false ) {
            if($filename != "." && $filename != ".."){

                $temp_path = $path . "/" . $filename;
                if ($stretch && is_dir($temp_path) ) {
//如果要全部文件
                    $base_filename = $base_name.$filename . "/";
                    $arr = array_merge($arr, self::allFile($temp_path,true ,$base_filename));

                }else{
                    array_push($arr, $base_name.$filename);
                }
//                echo $filename."<br>";
            }
        }
        closedir($handler);
        return $arr;

    }



    /**
     * 下载文件
     * @param $content
     * @param $file_name
     */
    public static function download($content, $file_name)
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-Disposition: attachment; filename=".$file_name);
        echo $content;
        exit();

    }


    /**
     * 递归创建目录
     * @param $dir
     * @return bool
     */
    public static function recursionMkDir($dir)
    {
        return is_dir( $dir ) or self::recursionMkDir(dirname( $dir )) and mkdir( $dir , 0777);

    }

    public static function recursionFilePutContents($file_path,$contents)
    {
        $dest = pathinfo($file_path);
        $dir = $dest['dirname'];
        FileUtil::recursionMkDir($dir);
        file_put_contents($file_path, $contents);

    }

//file_put_contents($des_path,$contents);


    /**
     * 删除文件夹及所有子目录
     * @param $dir_name
     * @param bool $echo 是否输出删除提示
     */
    public static function rmDir($dir_name, $echo=true)
    {
        if ( $handle = opendir( "$dir_name" ) ) {
            while ( false !== ( $item = readdir( $handle ) ) ) {
                if ( $item != "." && $item != ".." ) {
                    if ( is_dir( "$dir_name/$item" ) ) {
                        self::rmDir("$dir_name/$item" ,$echo);
                    } else {
                        if( unlink( "$dir_name/$item" )  && $echo ) {

                            echo "成功删除文件： $dir_name/$item\n";
                        }
                    }
                }
            }
            closedir( $handle );
            if( rmdir( $dir_name )   && $echo )echo "成功删除目录： $dir_name\n";
        }

    }


    /**
     * 这个没有sleep 适合批量转移文件
     * @param $source_path
     * @param $dest_path
     */
    public static function moveFile($source_path, $dest_path)
    {
        $dest_dir = pathinfo($dest_path)['dirname'];
        FileUtil::recursionMkDir($dest_dir);
        rename($source_path, $dest_path);
    }

    /**
     * 复制文件夹
     *
     * @param string $oldDir
     * @param string $aimDir
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
  public static function copyDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            FileUtil::recursionMkDir($aimDir);
        }
        $dirHandle = opendir($oldDir);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                FileUtil :: copyFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                FileUtil :: copyDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        return closedir($dirHandle);
    }

    public static function move($old,$des)
    {
        if (!file_exists($old)) {
            throw new \Exception("源不存在".$old);
        }
        if (is_file($old)) {
            dump($des);
            self::copyFile(...func_get_args());
            sleep(2);
            self::unlinkFile($old);
        }else{
            self::copyDir(...func_get_args());
            sleep(2);
            self::rmDir($old);

        }

    }

    /**
     * @param $old
     * @param $des
     * @param bool $overwrite
     * @throws \Exception
     */
    public static function publish($old, $des, $overwrite=false)
    {
//        dump($old);
        if (!file_exists($old)) {
            throw new \Exception("源不存在".$old);
        }

        if (is_file($old)) {
//            dump($des);
            self::copyFile(...func_get_args());
        }else{
            self::copyDir(...func_get_args());

        }

    }


    public static function unlinkFileOrDir($aimUrl)
    {
        if (!file_exists($aimUrl)) {
            dump("源不存在" . $aimUrl);
            return false;
//            throw new \Exception("源不存在".$aimUrl);
        }

        if (is_file($aimUrl)) {
//            dump($des);
            self::unlinkFile(...func_get_args());
        }else{
            self::rmDir(...func_get_args());

        }

    }


    /**
     * 删除文件
     *
     * @param string $aimUrl
     * @return boolean
     */
   public static  function unlinkFile($aimUrl) {
        if (file_exists($aimUrl)) {
            unlink($aimUrl);
            return true;
        } else {
            return false;
        }
    }


    /**
     * 复制文件
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
   public static  function copyFile($fileUrl, $aimUrl, $overWrite = false) {

        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite == false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite == true) {
            FileUtil :: unlinkFile($aimUrl);
        }


       $aimDir = dirname($aimUrl);
        FileUtil :: recursionMkDir($aimDir);
        copy($fileUrl, $aimUrl);
        return true;
    }



}
