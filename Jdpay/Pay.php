<?php
/**
 * @description:
 * @author: luerfeng
 * @time: 15/10/9-下午4:07
 * @version: 1.0
 */

namespace Payment\Jdpay;

class Payment_Jdpay_Pay
{
    // 费用的换算单位
    private  static $CURRENCY_RATE = 100;

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 京东快捷支付 PC 端表单构建
     * @param $order_param
     * @return string
     */
    public function buildPcPayForm($order_param)
    {
        $param = array(
            'version'            => $this->config['version'],
            'token'              => $this->get($order_param['token'], ''),
            'currency'           => $this->config['currency'],
            'merchantNum'        => $this->config['merchant_num'],
            'merchantRemark'     => $this->get($this->config['merchant_remark'], ''),
            'tradeNum'           => $order_param['out_trade_no'],
            'tradeName'          => $order_param['subject'],
            'tradeDescription'   => $order_param['body'],
            'tradeTime'          => date('Y-m-d H:i:s', time()),
            'tradeAmount'        => intval($order_param['total_fee'] * self::$CURRENCY_RATE),
            'notifyUrl'          => $order_param['notify_url'],
            'successCallbackUrl' => $order_param['return_url'],
            'ip'                 => $order_param['ip'],
        );
        Payment_Jdpay_Api::sortParam($param);
        $prestr = Payment_Jdpay_Api::createSignString($param, Payment_Jdpay_Config::$pcUnsignFields);
        $sha256_str = hash ( "sha256", $prestr, true);
        $param['merchantSign'] = Payment_Jdpay_Api::signRSA($sha256_str, $this->config['private_key_path']);
        $html = Payment_Jdpay_Api::buildForm($param, $this->config['pay_url']);
        return $html;
    }

    /**
     * @param      $var
     * @param null $default
     * @return null
     */
    private function get(&$var, $default=null)
    {
        return isset($var) ? $var : $default;
    }

    /**
     * @param $order_param
     * @return string
     */
    public function buildWapPayForm($order_param)
    {
        $param = array(
            'version'            => $this->config['version'],
            'token'              => $this->get($order_param['token'], ''),
            'merchantNum'        => $this->config['merchant_num'],
            'merchantRemark'     => $this->get($this->config['merchant_remark'], ''),
            'tradeNum'           => $order_param['out_trade_no'],
            'tradeName'          => $order_param['subject'],
            'tradeDescription'   => $order_param['body'],
            'tradeTime'          => date('Y-m-d H:i:s', time()),
            'tradeAmount'        => intval($order_param['total_fee'] * self::$CURRENCY_RATE),
            'currency'           => $this->config['currency'],
            'successCallbackUrl' => $order_param['return_url'],
            'failCallbackUrl'    => $order_param['error_notify_url'],
            'notifyUrl'          => $order_param['notify_url'],
        );
        Payment_Jdpay_Api::sortParam($param);
        $prestr = Payment_Jdpay_Api::createSignString($param, Payment_Jdpay_Config::$mobileUnsignFields);
        $sha256_str = hash ( "sha256", $prestr);
        $param['merchantSign'] = Payment_Jdpay_Api::signRSA($sha256_str, $this->config['private_key_path']);
        $html = Payment_Jdpay_Api::buildForm($param, $this->config['pay_url']);
        return $html;
    }

    public function buildGatewayPayForm($order_param)
    {

    }
}