<?php
namespace App\Lib\Common\Dictionary\FakerTraits;

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2019/4/11
 * Time: 10:49
 */
trait FakerTest{


    public static function arr($show_text="name")
    {

        return [
            [$show_text => '刘一', 'id' => 1],
            [$show_text => '陈二', 'id' => 2],
            [$show_text => '张三', 'id' => 3],
            [$show_text => '李四', 'id' => 4],
            [$show_text => '王五', 'id' => 5],
            [$show_text => '赵六', 'id' => 6]
        ];
    }


    public static function selectOptions()
    {
        return collect(static::arr())->pluck('name','id')->toArray();
    }


}
