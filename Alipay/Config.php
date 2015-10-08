<?php
/**
 * @description:
 * @author: luerfeng
 * @time: 15/9/29-下午6:22
 * @version: 1.0
 */

namespace Payment\Alipay;

class Payment_Alipay_Config
{
    /**
     * @var array
     */
    public static $platformConfig = array(
        'pay_url' => 'https://mapi.alipay.com/gateway.do',
    );

    public static $pcSignFields = array(
        '_input_charset', 'service','partner','notify_url','return_url', 'error_notify_url',
        'out_trade_no', 'subject', 'payment_type', 'total_fee', 'seller_id', 'seller_email',
        'body', 'show_url', 'it_b_pay',
    );

    public static $appSignFields = array(
        'service', 'partner', '_input_charset', 'notify_url', 'app_id', 'appenv',
        'out_trade_no', 'subject', 'payment_type', 'seller_id', 'total_fee', 'body',
        'it_b_pay', 'extern_token'
    );

    public static $wapSignFields = array(
        'service', 'partner', 'notify_url', 'return_url', 'appenv',
        'out_trade_no', 'subject', 'payment_type', 'total_fee', 'seller_id',
        'body', 'show_url', 'it_b_pay', 'extern_token', 'otherfee', 'airticket'
    );

    /**
     * @var array
     */
    public static $sellerConfig = array(
        'partner'       => '20880123456789012',
        'seller_email'  => 'luerfeng2012@gmail.com',
        'key'           => '494xm4l0rszdpww5gcnckuutbsb3hlq',
        'sign_type'     => 'MD5',
        'input_charset' => 'utf-8',
        'cert_path'     => '/tmp/alipay/cert.pem',
        'transport'     => 'http',
    );
}