<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 12/23/2016
 * Time: 11:15 AM
 */

namespace App\Tools;

class DataHandle
{
    //数组转换字符串，只保留整型
    public static function implodeWithInteger($glue,$arr = []){
        if (count($arr)){
            $str = '';
            foreach ($arr as $v){
                if (ctype_digit($v)){
                    $str = $str ? $str.$glue.$v : $v;
                }
            }
            return $str;
        }else{
            return '';
        }
    }

    /**
     * 导出数据为csv
     * @param unknown $data		一个二维数组,结构如同从数据库查出来的数组
     * @param unknown $title	excel的第一行标题,一个数组,如果为空则没有标题
     * @param string $filename	下载的文件名
     */
    public static function exportCsv($data=array(),$title=array(),$fileName='report'){

        $os_name=php_uname();
        if(strpos($os_name,"Linux")!==false){
            $rsName = $fileName;//linux
        }else if(strpos($os_name,"Windows")!==false){
            $rsName = iconv("UTF-8", "GBK", $fileName);//windows
        }

        $rs = '';
        $separator = ',';
        if (!empty($title)){
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GBK",$v);
            }
            $title= implode($separator, $title);
            $rs .= "$title\n";
        }
        $csvData = array();
        if (!empty($data)){
            foreach($data as $key=>$val){
                foreach ($val as $ck => $cv) {
                    $csvData[$key][$ck] = iconv("UTF-8","GBK", $cv);
                }
                $csvData[$key] = implode($separator, $csvData[$key]);
            }
            $rs .= implode("\n",$csvData);
        }
        //写入文件
        $path = 'Uploads/export/'.date('Y').'/'.date('m').'/'.date('d');
        $mkdirResult = !file_exists($path) ? mkdir($path,0777,true) : true;
        if (!$mkdirResult){		//创建目录失败
            echo '系统权限不足，导出失败';
            die;
        }
        file_put_contents($path.'/'.$rsName.'.csv',$rs);
        unset($rs);
        header('Location:'.$_ENV['APP_URL'].'/'.$path.'/'.$fileName.'.csv');
    }

    //金额单位转换
    public static function moneyFormat($money){
        return intval($money / 10000);
    }

    //时间格式化
    public static function timeFormat($time,$format='Y.m.d H:i'){
        return date($format, $time);
    }

    /**
     * html图片地址替换
     * @param $url
     * @param $str
     * @return mixed
     */
    public static function htmlImgSrcDeal($url, $str){
        $pattern = "/(<[img|IMG].*?src=['|\"])(?!http)(.*?)(['|\"].*?[\/]?>)/";
        $str = preg_replace($pattern,'${1}'.$url.'${2}${3}',$str);
        return $str;
    }

    /**
     * 文件地址转换
     * @param $url
     * @param $str
     * @return mixed
     */
    public static function htmlFileHrefDeal($url, $str){
        $pattern = "/(<[a|A].*?href=['|\"])(?!http)(.*?)(['|\"].*?[\/]?>)/";
        $str = preg_replace($pattern,'${1}'.$url.'${2}${3}',$str);
        return $str;
    }

    public static function stringHide($string, $start, $end){
        $s = '';
        for ($i=0; $i<$end-$start; $i++){
            $s .= '*';
        }
        if ($s == ''){
            return '';
        }

        return mb_substr($string,0,$start,'utf-8').$s.mb_substr($string,$end,strlen($string)-$end,'utf-8');
    }

    public static function _strtotime($str, $format='Y-m-d H:i:s'){
        return strtotime(date($format, strtotime($str)));
    }
}