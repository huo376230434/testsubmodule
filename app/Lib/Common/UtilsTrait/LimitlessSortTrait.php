<?php
namespace App\Lib\Common\UtilsTrait;



trait LimitlessSortTrait {


//    以下五个方法是以前项目用的，放在这里备用

    //组合一维数组
     Public static function unlimitedForLevel($cate,$html='',$pid=0,$level=0,$pid_name="pid",$id_name='id'){
         !$html && $html='&nbsp;&nbsp;--';
         $arr = array();
        foreach($cate  as $val){
            if($val[$pid_name] == $pid){
                $val['level']= $level+1;
                $val['html']=str_repeat($html,$level);
                $arr[]=$val;
                $arr = array_merge($arr,self::unlimitedForLevel($cate,$html,$val[$id_name],$level+1,$pid_name,$id_name));
            }
        }
        return $arr;
    }





    //组合多维数组
     Public static  function unlimitedForLayer($cate,$pid=0,$pid_name="pid",$id_name='id'){
        $arr = array();
        foreach($cate as $v){
            if($v[$pid_name]==$pid){
                $v['children']=self::unlimitedForLayer($cate,$v[$id_name],$pid_name,$id_name);
                $arr[]=$v;
            }
        }
        return $arr;
    }


    //组合多维集合
    Public static  function unlimitedForLayerCollect($cate,$pid=0,$pid_name="pid",$id_name='id'){
        $arr = collect();
        foreach($cate as $v){
            if($v->$pid_name==$pid){
                $v->children=self::unlimitedForLayer($cate,$v->$id_name,$pid_name,$id_name);
//                $arr[]=$v;
                $arr->push($v);
            }
        }
        return $arr;
    }


    //传递一个子分类ID返回所有的父级分类，包括自身
     Public static function getUnlimitedParents($cate,$id,$pid_name="pid",$id_name='id'){
        $arr=array();
        foreach($cate as $v){
            if($v[$id_name]==$id){
                $arr[]=$v;
                $arr=array_merge(self::getUnlimitedParents($cate,$v[$pid_name],$pid_name,$id_name),$arr);
            }
        }
        return $arr;
    }

    //传递一个子分类ID返回所有的父级ID，包括自身
     Public  static function getUnlimitedParentIds($cate,$id,$pid_name="pid",$id_name='id'){
        $arr=array();
        foreach($cate as $v){
            if($v[$id_name]==$id){
                $arr[]=$v[$id_name];
                $arr=array_merge(self::getUnlimitedParentIds($cate,$v[$pid_name],$pid_name,$id_name),$arr);
            }
        }
        return $arr;
    }


    //传递一个父级ID返回所有子分类ID
     Public  static function getUnlimitedChildsId($cate,$pid,$pid_name="pid",$id_name='id'){
        $arr = array();
        foreach($cate as $v){
            if($v[$pid_name]==$pid){
                $arr[]=$v[$id_name];
                $arr=array_merge($arr,self::getUnlimitedChildsId($cate,$v[$id_name],$pid_name,$id_name));
            }
        }
        return $arr;
    }

    //传递一个父级ID返回所有子分类
     Public static function getUnlimitedChilds($cate,$pid,$pid_name="pid",$id_name='id'){
        $arr = array();
        foreach($cate as $v){
            if($v[$pid_name]==$pid){
                $arr[]=$v;
                $arr=array_merge($arr,self::getUnlimitedChilds($cate,$v[$id_name],$pid_name,$id_name));
            }
        }
        return $arr;
    }



}
