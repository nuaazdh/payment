<?php
/**
 * @description:
 * @author: luerfeng
 * @time: 15/10/9-下午4:06
 * @version: 1.0
 */

namespace Payment\Jdpay;

class Payment_Jdpay_Api
{
    /**
     * 构建京东支付待签名的字符串
     * @param array $param
     * @param array $unsignFields
     * @return bool|string
     */
    public static function createSignString($param, array $unsignFields = array())
    {
        if(empty($param) || !is_array($param))
            return false;

        $tmpArray = array();
        foreach($param as $key => $value)
        {
           if(in_array($key, $unsignFields))
               continue;
            $tmpArray[] = sprintf('%s=%s', $key, $value);
        }
        return implode('&', $tmpArray);
    }

    /**
     * @param string    $data  待签名字符串
     * @param string    $private_key_path  私钥文件路径
     * @param int $algorithm 签名算法参数
     * @return bool|string  签名结果
     */
    public static function signRSA($data, $private_key_path, $algorithm = OPENSSL_PKCS1_PADDING)
    {
        var_dump($private_key_path);
        if(!file_exists($private_key_path))
            return false;

        $key = file_get_contents($private_key_path);
        $pi_key =  openssl_pkey_get_private( $key );
        $encrypted="";
        openssl_private_encrypt($data,$encrypted,$pi_key, $algorithm);//私钥加密
        $encrypted = base64_encode($encrypted);
        return $encrypted;
    }

    public static function sortParam(&$param)
    {
        ksort($param);
        reset($param);
    }

    public static function buildForm($param, $url, $method='post')
    {
        if(empty($param) || !is_array($param))
            return '';
        $html = sprintf('<form id="payform" name="payform" action="%s" method="%s">',
            $url, $method);
        foreach($param as $key => $value)
        {
            $html .= sprintf('<input type="hidden" name="%s" value="%s" />',
                $key,
                $value);
        }
        $html .= '</form><script>document.forms["payform"].submit();</script>';
        return $html;
    }

}