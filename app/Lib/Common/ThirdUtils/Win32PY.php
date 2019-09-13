<?php
namespace App\Lib\Common\ThirdUtils;

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 20:03
 */
class Win32PY{

    protected $tips = <<<DDD
请确认 python3.7 与对应的win32模块是否安装
python3.7:
https://www.python.org/ftp/python/3.7.2/python-3.7.2-amd64.exe

win32(amd64):
https://github-production-release-asset-2e65be.s3.amazonaws.com/108187130/b4964080-c33c-11e8-9725-2291dbefd1e2?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAIWNJYAX4CSVEH53A%2F20190310%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20190310T102504Z&X-Amz-Expires=300&X-Amz-Signature=54bb338188b0df8a6c1d3896c5de644d9d418ca629b3e612c2c43332e4f76309&X-Amz-SignedHeaders=host&actor_id=19550875&response-content-disposition=attachment%3B%20filename%3Dpywin32-224.win-amd64-py3.7.exe&response-content-type=application%2Foctet-stream
DDD;

    public $keys_map = [
        "Backspace" => 8,
        "Tab" => 9,
        "Clear" => 12,
        "Enter" => 13,
        "Shift" => 16,
        "Control" => 17,
        "Alt" => 18,
        "Caps Lock" => 20,
        "Esc" => 27,
        "Space" => 32,
        "Page Up" => 33,
        "Page Down" => 34,
        "End" => 35,
        "Home" => 36,
        "Left Arrow" => 37,
        "Up Arrow" => 38,
        "Right Arrow" => 39,
        "Down Arrow" => 40,
        "Snapshot" =>44,//截屏时需要左windows 与这个键一起按，（在win10)
        "Insert" => 45,
        "Delete " => 46,
        "Help" => 47,
        "LWIN" => 91,//左windows键
        "Num Lock" => 144,
    ];


    public function __construct()
    {
        $this->keyMap();
    }

    public  function keyMap()
    {
//        键位码只有数字与大写字母与asc码对应
//        数字0-9: 48-57
        $addor_keys = [];
        for ($i=48;$i<=57;$i++)
        {
            $addor_keys[chr($i)] = $i;
        }

        //     大写字母A-Z: 65-90
        for ($i=65;$i<=90;$i++){
            $addor_keys[chr($i)] = $i;

        }

        // F1-F12
        for($i=112;$i<=123;$i++){
            $key = $i-111;
            $key = "F" . $key;
            $addor_keys[$key] = $i;
        }

//    以下是特殊值:

        $this->keys_map = array_merge($this->keys_map, $addor_keys);
//        dump($this->keys_map);
    }


    public function press(...$params)
    {
        $str_keys = "";
        foreach ($params as $param) {
            $str_keys .= " ".$this->keys_map[$param];
        }
        dump($str_keys);
        $res = shell_exec("python ".$this->pyScript()." ".$str_keys);
        dump($res);

    }

    public function enter()
    {
        $this->press("Enter");

    }


    public function space()
    {
        $this->press("Space");
    }

    public function tab()
    {

        $this->press("Tab");

    }


    public function esc()
    {
        $this->press("Esc");
    }

    public function capsLock()
    {
        $this->press("Caps Lock");
    }

    public function screenShot()
    {
        $this->press("LWIN", "Snapshot");
    }

    protected function pyScript()
    {
        return storage_path("python/win32/keyboard.py");

    }

}
