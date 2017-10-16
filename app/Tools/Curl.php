<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2/5/2017
 * Time: 2:51 PM
 */
namespace App\Tools;

class Curl
{
    public static function request($url, $data = '', $type='POST', $header = array()) {
        $ch = curl_init ();
        $header['charset'] = "utf-8";
        $header['Accept-Charset'] = "utf-8";
        curl_setopt( $ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        //curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        if($type=='POST'){
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data);
        }

        $return_data = curl_exec ($ch);
        curl_close($ch);
        return $return_data;
    }


    public static function downloadFile($url,$filename) {
        if (strstr($url, 'http')){
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt ( $ch, CURLOPT_URL, $url );
            ob_start ();
            curl_exec ( $ch );
            $return_content = ob_get_contents ();
            ob_end_clean ();
            curl_close($ch);

            $fp= @fopen($filename,"a"); //将文件绑定到流
            fwrite($fp,$return_content); //写入文件
            return $filename;
        }else {
            return $url;
        }
    }

}