<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16
 * Time: 18:08
 */

namespace App\Lib\Common\CommonBase;
/**
 * Class EchartData
 * 只是简化Echart组件的数据封装
 * @package App\Lib\Common\CommonBase
 */
class EchartData {


     public static $base_color = ['#00b300','#00c3ef','#F8BB86','#c87f0a','#cc006a','#F27474'];


    /**
     * 饼状图数据
     * @param $source
     * @return array
     */
    public static function pieData($source)
    {

        //后台模板数据：
//        $data = [
//            'tooltip' => new \stdClass(),
//            'series' => [
//                [
//                    'name' => "访问来源",
//                    'type' => "pie",
//                    'radius' => "75%",
//                    'data' => [
//                        ['value' => 233,'name' =>"是"],
//                        [ 'value' => 333, 'name' => "kl"],
//                        [ 'value' => 33, 'name' => "ggg"]
//                    ],
//                    'color' => [
//                        '#338','#bbf',"#aa8"
//                    ]
//                ]
//            ]
//        ]; 
        
        
        
        
$color = isset($source['color'])&&is_array($source['color']) ? $source['color'] : self::$base_color;

        //后台模板数据：
        $data = [
            'tooltip' => new \stdClass(),
            'series' => [
                [
                    'name' => $source['name'],
                    'type' => "pie",
                    'radius' => "75%",
                    'data' => $source['data'],
                    'color' => $color
                ]
            ]
        ];
        return $data;
        
    }


    /**
     * 折线图数据
     * @param $source
     * @return array
     */
    public static function lineData($source)
    {


        //后台模板数据：
        
//        $line_data = [
//            'tooltip' => [
//                'axisPointer'=> [
//                    'type'=> 'cross'
//                ]
//            ],
//            'legend' =>[
//                'data' => ['销量','库存']
//            ],
//            'xAxis' => [
//                'data' => ["衬衫","羊毛衫","雪纺衫","裤子"],
//            ],
//            'yAxis' => new \stdClass(),
//            'series' => [
//                [
//                    'name' => '销量',
//                    'type' => 'line',
//                    'data' => [5,23,34,12],
//                    'color' => ["#775"]
//                ],
//                [
//                    'name' => '库存',
//                    'type' => 'line',
//                    'data' => [53,2,14,22],
//                    'color' => ["#999"]
//                ]
//            ]
//        ];
//
        return self::getBaseData($source, 'line');


    }


    /**
     * 获得渲染数据，为折线图和柱状图所用
     * @param $source
     * @param $type
     * @return array
     */
    protected static function getBaseData($source, $type)
    {
        $legend_data =  collect($source['data'])->pluck('name');
        $base_color_len = count(self::$base_color);
        foreach ($source['data'] as $k => $v) {
            $source['data'][$k]['type'] = $type;


            if(!isset($v['color'])){
                $source['data'][$k]['color'] =[ self::$base_color[$k % $base_color_len]];
            }
        }

        $return_data = [
            'tooltip' => [
                'axisPointer'=> [
                    'type'=> 'cross'
                ]
            ],
            'legend' =>[
                'data' => $legend_data
            ],
            'xAxis' => [
                'data' => $source['x_data'],
            ],
            'yAxis' => new \stdClass(),
            'series' =>$source['data']
        ];



        return$return_data;//返回数组

    }

    /**
     * 柱状图数据
     * @param $source
     * @return array
     */
    public static function histogramData($source)
    {

        return self::getBaseData($source, 'bar');

    }




}
