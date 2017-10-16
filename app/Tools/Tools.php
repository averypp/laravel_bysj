<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2/6/2017
 * Time: 10:48 AM
 */

namespace App\Tools;

use Illuminate\Support\Facades\Config;

class Tools
{
    /**
     * 检查手机格式
     * @param $mobile
     * @return bool
     */
    public static function checkMobile($mobile){
        if(!preg_match('/^1[34578]\d{9}$/', $mobile)){
            //手机号码格式不正确
            return false;
        }
        return true;
    }

    //记录文本
    public static function recordLog($file_name,$msg){
        $separator = '*********************************';
        $file_name = $file_name;
        $url = self::getUrl();
        @file_put_contents($file_name, $separator."\nTime:".date('Y-m-d H:i:s')."\nIP:".$_SERVER['REMOTE_ADDR']."\nUrl:".$url."\n$msg\n$separator\n", FILE_APPEND);
    }

    /**
     * 获取当前页面完整URL地址
     */
    public static function getUrl() {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }

    /**
     * 获取string中的参数
     * @param $par_data
     * @return array
     */
    public static function getPar($par_data){
        $return_data = [];
        if ($par_data != ''){
            $tmp = explode('&', $par_data);
            if ($tmp){
                foreach ($tmp as $v){
                    if ($v != ''){
                        $arr = explode('=', $v);
                        if ($arr){
                            $return_data[$arr[0]] = isset($arr[1]) ? $arr[1] : '';
                        }
                    }

                }
            }

        }
        return $return_data;
    }

    public static function pdf2png($pdf,$path=''){
        if(!extension_loaded('imagick')){
            return [];
        }
        if(!file_exists($pdf)){
            return [];
        }
        $IM = new \imagick();
        $IM->setResolution(72,72);
        $IM->setCompressionQuality(60);
        $IM->readImage($pdf);
//
        $iwidth = 640;


        foreach($IM as $key => $var){
            $var->setImageFormat('png');
            if ($path == ''){
                $path = 'Uploads/image/'.date('Y').'/'.date('m').'/'.date('d');
            }
            //以时间为目录名创建多层目录文件夹
            $mkdirResult = !file_exists($path)?mkdir($path,0777,true):true;
            if (!$mkdirResult){		//创建目录失败
                return [];
            }

            $filename = $path.'/'.md5($key.time()).'.png';
            if($var->writeImage($filename)==true){
                $p = new \imagick();
                $p->readImage($filename);
                $srcImage = $p->getImageGeometry(); //获取源图片宽和高
                //图片等比例缩放宽和高设置 ，根据宽度设置等比缩放
                if ($srcImage['width'] > $iwidth){
                    $newY = $iwidth * $srcImage['height'] / $srcImage['width'];
                    $p->thumbnailImage($iwidth, $newY);
                    $p->writeImage($filename);
                }

                $return[]= $filename;

            }
        }
        return $return;
    }

    public static function msgPush($msgInfo, $userInfo){
        $pathConfig = Config::get('pathConfig');
        $host = $pathConfig['01zcApi'];

        $data = [
            "message"=>[]
        ];
        if (count($userInfo)){
            foreach ($userInfo as $k=>$v){
                if (is_array($v) && count($v)){
                    $arr = [
                        "template_id"=>$k,
                        "project_title"=>$msgInfo['project_title'],
                        "project_charger"=>$msgInfo['project_charger'],
                        "demand_title"=>$msgInfo['demand_title'],
                        "demand_content"=>$msgInfo['demand_content'],
                        "dynamic_title"=>$msgInfo['dynamic_title'],
                        "created"=>time(),
                        "people"=>$v
                    ];
                    $data['message'][] = $arr;
                }
            }
            if (count($data['message'])){
                return Curl::request($host,http_build_query($data));
            }
        }
        return '';


    }
}