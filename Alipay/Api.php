<?php
/**
 * @description:
 * @author: luerfeng
 * @time: 15/9/29-下午6:22
 * @version: 1.0
 */

namespace Payment\Alipay;

class Payment_Alipay_Api
{
    /**
     * @param $param
     * @return string
     */
    public static function createLinkString($param)
    {
        $tmpArray = array();
        foreach($param as $key => $value)
        {
            $tmpArray[] = sprintf('%s=%s', $key, $value);
        }
        return implode('&',$tmpArray);
    }

    /**
     * @param $param array
     * @return string
     */
    public static function createLinkStringUrlencode($param)
    {
        $tmpArray = array();
        foreach($param as $key => $value)
        {
            $tmpArray[] = sprintf('%s="%s"', $key, urlencode($value));
        }
        return implode('&',$tmpArray);
    }

    /**
     *
     * @param array $param
     * @return mixed
     */
    public static function sortArray($param)
    {
        ksort($param);
        reset($param);
        return $param;
    }

    /**
     * @param $reserve_fields
     * @param $param
     * @return array
     */
    public static function filterArray($reserve_fields, &$param)
    {
        $tmp = array();
        foreach($reserve_fields as $key)
        {
            isset($param[$key]) and
                $tmp[$key] = $param[$key];
        }
        return $tmp;
    }

    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key_path 商户私钥文件路径
     * return 签名结果
     */
    public static function signRSA($data, $private_key_path)
    {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA 验签
     * @param $data array 待签名数据
     * @param $ali_public_key_path string 支付宝的公钥文件路径
     * @param $sign string 要校对的的签名结果
     * return bool 验证结果
     */
    public static function verifyRSA($data, $public_key_path, $sign)  {
        $pubKey = file_get_contents($public_key_path);
        $res = openssl_get_publickey($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }

    /**
     * 签名字符串
     * @param $prestr string 需要签名的字符串
     * @param $key string 私钥
     * return string 签名结果
     */
    public static function signMD5($prestr, $key)
    {
        $prestr = $prestr . $key;
        return md5($prestr);
    }

    /**
     * 验证签名
     * @param $prestr string 需要签名的字符串
     * @param $sign string 签名结果
     * @param $key string 私钥
     * return string 签名结果
     */
    public static function verifyMD5($prestr, $sign, $key)
    {
        $prestr = $prestr . $key;
        $mySgin = md5($prestr);

        if ($mySgin == $sign)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @param        $param array
     * @param        $url  string
     * @param string $method  string
     * @return string HTML
     */
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

    public static function buildUrl($param, $query_url)
    {
        return sprintf('%s?%s', $query_url, http_build_query($param));
    }

    /**
     * 远程获取数据，POST模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url string 指定URL完整路径地址
     * @param $cacert_url string 指定当前工作目录绝对路径
     * @param $para array 请求的数据
     * @param $input_charset string 编码格式。默认值：空值
     * return string 远程输出的数据
     */
    function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '') {

        if (trim($input_charset) != '') {
            $url = $url."_input_charset=".$input_charset;
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl,CURLOPT_POST,true); // post传输数据
        curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }

    /**
     * 远程获取数据，GET模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url string 指定URL完整路径地址
     * @param $cacert_url string 指定当前工作目录绝对路径
     * return string 远程输出的数据
     */
    function getHttpResponseGET($url,$cacert_url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }
}