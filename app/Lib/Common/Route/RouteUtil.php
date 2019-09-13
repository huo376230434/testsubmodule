<?php

namespace App\Lib\Common\Route;

use App\Lib\Common\CommonBase\FileUtil;
use App\Lib\Common\CommonBase\ScatteredUtil;
use App\Lib\Common\Dictionary\BaseDict;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/17
 * Time: 16:35
 */

class RouteUtil{

    /**
     * 生成自动映身路由的控制器
     * 规则: 在Admin,Http模块中的所有控制器，以Mp开头则表明自动映射
     * @param Router|null $router
     * @throws \ReflectionException
     */
    public static function mappedRouteFromController($module,$route_path=null)
    {


        $dir = app_path($module . "/Controllers/");
        $namespace_prefix = "App\\$module\Controllers\\";
        $snake_module = Str::snake($module);
        !$route_path && $route_path = app_path($module.'/'.$snake_module.'_route/'.'auto_'.$snake_module.'_route.php');
        if (!is_file($route_path)) {
            throw new \Exception('路由文件不存在'.$route_path);
        }
        $route_file_content = file($route_path);

        $file_names = FileUtil::allFile($dir);
        $file_names = static::getMpControllers($file_names,$namespace_prefix);
//            dump($file_names);
        foreach ($file_names as $file_name) {
            $true_file_name = substr($file_name, 0, strrpos($file_name, '.php'));

//            $true_file_name = rtrim($file_name,".php");
            $class_name = $namespace_prefix . $true_file_name;
            $reflection_class = new ReflectionClass($class_name);

            $route_prefix = static::getRoutePrefix( $file_name);
//                dump($class);
            $methods = ScatteredUtil::getPublicMethods($class_name) ? : [];
//                dump($methods);
            foreach ($methods as $method) {

                $reflection_method = $reflection_class->getMethod($method);
                $params = $reflection_method->getParameters();
                $end = "";
                //取到方法的参数
                foreach ($params as $param) {
                    $param_name = $param->getName();
                    $end .= '/{' . $param_name . "}";
                }

                $action = class_basename($class_name ) . '@' . $method;
//                    dump($action);
                $uri = $route_prefix . "/" . Str::camel($method).$end;
                static::pushToRoute($uri,$action,$route_file_content);
//                $router &&  $router->any(.$end,$action);
            }
        }

        //最终将路由写入文件
        file_put_contents($route_path,implode('',$route_file_content));
    }

    protected static  function pushToRoute($uri,$action,&$route_file_content)
    {
        $template = <<<DDD
\$router->any("$uri", "$action");

DDD;
        if(!collect($route_file_content)->some((function ($value,$key)use($uri,$action){
                    return Str::contains($value,$uri) && Str::contains($value,$action);
        }))){
            array_push($route_file_content, $template);
        }
    }


    protected static function getRoutePrefix($file_name)
    {

//        dump($file_name);
//        dump(rtrim($file_name,"ontroller.php"));
//        return strtolower(rtrim($file_name,"Controller.php"));
        return strtolower(str_replace("Controller.php", "", $file_name));
    }


    protected static function getMpControllers($file_arr,$namespace_prefix)
    {
        $col = collect($file_arr);
        return $col->filter(function($value,$key) use ($namespace_prefix){
            $class_name = $namespace_prefix . $value;
            if (Str::endsWith($value, ".php")) {
                $class_name = substr($class_name, 0, strrpos($class_name, '.php'));
                $class = new ReflectionClass( $class_name);
                return $class->hasProperty("map_route");
            }
            return false ;
        });
    }

}
