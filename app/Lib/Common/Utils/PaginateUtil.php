<?php

namespace App\Lib\Common\Utils;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Uuid;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/17
 * Time: 16:35
 */

class PaginateUtil{




   public static function procedureData($res, $page_size = null)
    {
        if (is_null($page_size)) {
            $page_size = request('page_size', 10);
        }
        $data = $res['data'];
        $total = $res['total_count'];
//            dd($projects);
        $data = new LengthAwarePaginator($data, $total, $page_size);
        $data = $data->setPath(url()->current())->appends(request()->all())->onEachSide(1);
        return $data;

    }



   public static function modelData(\Illuminate\Contracts\Pagination\LengthAwarePaginator $paginate)
    {
        return $paginate->appends(request()->all())->onEachSide(1);
    }



}
