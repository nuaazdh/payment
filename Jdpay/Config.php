<?php
/**
 * @description:
 * @author: luerfeng
 * @time: 15/10/9-下午4:07
 * @version: 1.0
 */

namespace Payment\Jdpay;

class Payment_Jdpay_Config
{
    // 与支付平台相关的配置
    public static $platformConfig = array(
        'pc' => array(  // 快捷支付 PC端
            'version'   => '1.1.5',     // 版本号
            'currency'  => "CNY",       // 币种
            'pay_url'   => 'https://plus.jdpay.com/nPay.htm',        //支付请求地址
            'query_url' => 'https://m.jdpay.com/wepay/query',
        ),
        'wap' => array( // 快捷支付 手机网页端
           'version'         => '1.0',
           'currency'        => 'CNY',
           'pay_url'         => 'https://m.jdpay.com/wepay/web/pay',
        ),
        'gateway' => array( // 网银支付
            'currency'  => 'CNY',
            'pay_url'   => 'https://Pay3.chinabank.com.cn/PayGate',
        ),
    );

    public static $pcUnsignFields = array('merchantSign', 'version', 'successCallbackUrl', 'forPayLayerUrl');

    public static $mobileUnsignFields = array('merchantSign', 'token', 'version');

    public static $gatewaySignFields = array();

    // 与商户商户相关的配置
    public static $sellerConfig = array(
        'merchant_num'    => '22294531',
        'merchant_remark' => '蚂蜂窝旅游网',
        'md5_key'         => 'test',
        'des_key'         => 'ta4E/aspLA3lgFGKmNDNRYU92RkZ4w2t',
        'gateway_key'     => 'test',
        'private_key_path' => '/Users/nuaazdh/workspace/payment/payment/Jdpay/cert/seller_rsa_private_key.pem',
    );
}