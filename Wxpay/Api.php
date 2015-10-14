<?php
/**
 * @description:
 * @author: luerfeng
 * @time: 15/10/10-下午12:04
 * @version: 1.0
 */

namespace Payment\Wxpay;

class Payment_Wxpay_Api
{

    public static function  generateQRcodeUrl($url)
    {
        return '';
    }

    /**
     * 将数组转为微信请求的XML格式
     * @param $param
     * @return string
     * @throws \Exception
     */
    public static function toXML($param)
    {
        if(!is_array($param)
            || count($param) <= 0)
        {
            throw new \Exception("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($param as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws \Exception
     */
    public static function fromXML($xml)
    {
        if(!$xml){
            throw new \Exception("xml数据异常！");
        }
        $ret = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $ret;
    }

    /**
     * 格式化参数格式化成url参数
     * @param array $param
     * @return string
     */
    public static function toURL($param)
    {
        $buff = array();
        foreach ($param as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff[] = $k . "=" . $v;
            }
        }
        return implode('&', $buff);
    }

    /**
     * @param $param
     * @param $key
     * @return 签名
     */
    public static  function SignUnifiedOrder($param, $key)
    {
        $sign_fields = array('appid','mch_id','device_info','nonce_str','body','detail','attach','out_trade_no',
                             'total_fee', 'spbill_create_ip','time_start','time_expire', 'goods_tag',
                             'notify_url','trade_type','product_id','limit_pay','openid');
        $sign_array = self::filterArray($sign_fields, $param);
        return self::signMD5($sign_array, $key);
    }

    /**
     * 对统一下单返回和异步通知支付结果的进行验签
     * @param $param array 返回的XML转换后的数组
     * @param $key   app对应的key
     * @return bool  是否验签通过
     */
    public static function verifyResponseSign($param, $key)
    {
        if(!isset($param['sign']))
            return False;
        $sign_o = $param['sign'];
        unset($param['sign']);
        $sign_r = self::signMD5($param, $key);
        return $sign_r == $sign_o;
    }

    /**
     * App支付签名
     * @param $param
     * @param $key
     * @return string 签名
     */
    public static  function  SignApp($param, $key)
    {
        $sign_fields = array('appid', 'partnerid', 'prepayid', 'noncestr', 'timestamp', 'package');
        $sign_array = self::filterArray($sign_fields, $param);
        return self::signMD5($sign_array, $key);
    }

    /**
     * JSAPI支付签名
     * @param $param
     * @param $key
     */
    public static function SignJSApi($param, $key)
    {
        $sign_fields = array('appId', 'timeStamp', 'nonceStr', 'package', 'signType');
        $sign_array = self::filterArray($sign_fields, $param);
        return self::signMD5($sign_array, $key);
    }

    public static function SignWap($param, $key)
    {
        $sign_fields = array('appid', 'timestamp', 'noncestr', 'package', 'prepayid');
        $sign_array = self::filterArray($sign_fields, $param);
        return self::signMD5($sign_array, $key);
    }

    public static function filterArray($sign_fields, $param)
    {
        $sign_array = array();
        foreach($sign_fields as $field)
        {
            if(isset($param[$field]) && !empty($param[$field]))
                $sign_array[$field] = $param[$field];
        }
        return $sign_array;
    }

    /**
     * 生成MD5签名
     * @param array $param 待签名数组
     * @param string $key 签名 key
     * @return string 签名串
     */
    public static function signMD5($param, $key)
    {
        //签名步骤一：按字典序排序参数
        ksort($param);
        $string = self::toURL($param);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$key;
        var_dump($string);
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 产生随机字符串，不长于32位
     * @param int $length 字符串长度
     * @return string 随机字符串
     */
    public static function generateNonstr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     */
    public static function postXmlQuery($xml, $url, $useCert = false, $userProxy = false,
                                       $second = 30, $config = array())
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        // 如果有配置代理这里就设置代理
        if($userProxy){
            curl_setopt($ch,CURLOPT_PROXY, $config['proxy_host']);
            curl_setopt($ch,CURLOPT_PROXYPORT, $config['proxy_port']);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $config['cert_path']);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, $config['key_path']);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }
}