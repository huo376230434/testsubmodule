<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 10:19
 */
namespace App\Lib\Common\CommonBase;
use Exception;
class CsvExport{


    /**
     * 导出csv
     * @param $file_name
     * @param $data
     * @param bool $file_dir
     * @return string
     */
    public static function export($file_name, $data, $file_dir=false)
    {

        $enter_code = "\n";

        $titles = [];
        if (!empty($data)) {
            $titles = array_keys($data[0]);
        }

        $output = implode(',', $titles).$enter_code;

        foreach ($data as $row) {
            $row = array_only($row, $titles);
            $output .= implode(',', array_dot($row)).$enter_code;
        }

        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$file_name\"",
        ];
        if ($file_dir) {
//            windows环境要转成gbk
        PHP_OS=="WINNT"  &&   $file_name=iconv("UTF-8","gb2312", $file_name);
            $total_path = $file_dir.$file_name;
            file_put_contents($total_path,$output);
            return $total_path;
        }else{
            response(rtrim($output, "\n"), 200, $headers)->send();
//            exit();
        }

    }

}
