<?php

namespace App\Lib\Common\CommonBase;



class UrlUtil{


    public static  function urlIsEqual($url, $to_equal, $ignore_params = [['_pjax', 'pjax-container']])
    {
        $url = static::urlAdjustParams($url,$ignore_params);
        $to_equal =   static::urlAdjustParams($to_equal,$ignore_params);
        return $url == $to_equal;
    }


    public static   function urlAdjustParams($url,$exclude=[],$add=[])
    {
        $temp_data = static::dwParseUrl($url);

        $keys = array_keys($temp_data['query']);
        //剔除要排除的参数
        foreach ($exclude as $item) {
            //如果$item是数组，说明要判断精确的值
            if (is_array($item)) {
                [$exclude_key,$exclude_value] = $item;
                if ($temp_data['query'][$exclude_key] ?? null == $exclude_value) {
                    unset($temp_data['query'][$exclude_key]);
                };
            }else if (in_array($item, $keys)) {
                unset($temp_data['query'][$item]);
            }
        }
        //添加要加的参数
        foreach ($add as $add_key =>  $add_item) {
            $temp_data['query'][$add_key] = $add_item;
        }
        ksort($temp_data['query']);
        //重新生成url
        return static::dwRegenerateUrl($temp_data);

    }




    public static   function dwRegenerateUrl($url_parse_arr)
    {
        if (!isset($url_parse_arr['host'])) {
            return null;
        }
        $path = $url_parse_arr['path'] ?? '';
//        dump($url_parse_arr);
        return $url_parse_arr['scheme']
            . '://' . $url_parse_arr['host']
            . $path
            . '?'
            . http_build_query($url_parse_arr['query']);
    }



    public static  function dwParseUrl($url )
    {
        $url = urldecode($url);

        $arr = parse_url($url);

        if (isset($arr['query'])) {
            $temp = explode('&', $arr['query']);
            $new_query_arr = [];

            foreach ($temp as $item) {
                [$key, $value] = explode('=', $item);
                $new_query_arr [$key] = $value;
            }
            $arr['query'] = $new_query_arr;
        }else{
            $arr['query'] = [];
        }
        ksort($arr['query']);

        return $arr;
    }





    public static  function urlWithRedirect($url,$redirect_url=null,$other_querys=[])
    {
        if ($redirect_url === null ) {
            //没有 _redirect_url时默认为返回，即自己
            $other_querys['_redirect_url'] = request()->fullUrl();
        }
        if (starts_with('http', $url)) {
            return  static::urlAdjustParams($url,[],$other_querys);
        }
        return url($url) . '?'.http_build_query($other_querys);

    }



    /**
     * 注意，这个函数只能判断url 的path部分 如 /admin/ad/\d+   /admin/ad/\w+  /admin/ad/*
     * @param $pattern
     * @param $value
     * @return bool
     */
   public static function urlPregMatch($pattern, $value)
    {
        $pattern = str_replace('#', '\#',$pattern);
        $pattern = str_replace('*', '.*', $pattern);
        $pattern = "#^". $pattern . "\z#u";
        return preg_match($pattern, $value) === 1;
    }

}
