<?php
namespace App\Lib\Common\Utils;


/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 20:03
 */
class UIHelper{

    public static function popover($value,$max_word = 20)
    {
        //    return "<span style='max-width: 200px;' class='d-inline-block text-ellipsis'>{$value}</span>";
        if (mb_strwidth($value, 'UTF-8') > $max_word) {
            return  '<span style="text-decoration:none" href="javascript:void(0);" data-toggle="popover" data-html="true" data-placement="auto top" data-trigger="hover"  title="" data-content="'.$value.'">'.str_limit($value,$max_word).'</span><script>	
$(function() {
  $("[data-toggle=\'popover\']").popover();

})
</script>';
        }else{
            return $value;

        }
    }

}
