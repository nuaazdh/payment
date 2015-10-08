<?php
/**
 * @description:
 * @author: luerfeng
 * @time: 15/9/29-下午6:23
 * @version: 1.0
 */

namespace Payment\Alipay;

use Payment\Alipay\Payment_Alipay_Api;

class Payment_Alipay_Pay
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param array $order_param
     * @param string $service
     * @return string
     */
    public function buildPcPayForm($order_param, $service='create_direct_pay_by_user')
    {
        $param = array(
            'service' => $service,
            'partner' => $this->config['partner'],
            'seller_email' => $this->config['seller_email'],
            '_input_charset' => $this->config['input_charset'],
            'sign_type' => strtoupper($this->config['sign_type']),
            'notify_url' => $order_param['notify_url'],
            'return_url' => $order_param['return_url'],
            'error_notify_url' => $order_param['error_notify_url'],
            'out_trade_no' => $order_param['out_trade_no'],
            'subject' => $order_param['subject'],
            'total_fee' => $order_param['total_fee'],
            'payment_type' => 1,
            'body' => $order_param['body'],
            'show_url' => $order_param['show_url'],
            'seller_id' => $this->config['partner'],
        );
        $tmp = Payment_Alipay_Api::filterArray(Payment_Alipay_Config::$pcSignFields, $param);
        $tmp = Payment_Alipay_Api::sortArray($tmp);
        $prestr = Payment_Alipay_Api::createLinkString($tmp);
        $param['sign'] = Payment_Alipay_Api::signMD5($prestr, $this->config['key']);
        $url = sprintf('%s?_input_charset=%s', $this->config['pay_url'], $param['_input_charset']);
        $html = Payment_Alipay_Api::buildForm($param, $url);
        return $html;
    }

    public function buildAppPayParam()
    {

    }

    public function buildWapPayForm()
    {

    }

}