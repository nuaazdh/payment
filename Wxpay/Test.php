<?php
/**
 * @description:
 * @author: luerfeng
 * @time: 15/10/10-下午12:04
 * @version: 1.0
 */


namespace Payment\Wxpay;

include 'Api.php';
include 'Config.php';
include 'Pay.php';

$platform = $_GET['platform'];

switch($platform)
{
    case 'app':
        testAppPay();
        break;
    case 'wap':
        testWapPay();
        break;
    case 'js':
        testJsPay();
        break;
    case 'native':
        testNativePay();
        break;
    default:
        echo 'undefined platform',PHP_EOL;
}

function testAppPay()
{
    $config = array_merge(Payment_Wxpay_Config::$platformConfig, Payment_Wxpay_Config::$sellerConfig);

    $param = array(
        'notify_url'   => 'http://www.mafengwo.cn/alipay/notify_url.php',
        'return_url'   => 'http://www.baidu.com',
        'out_trade_no' => '1514454139936301',
        'subject'      => 'xxx三日游',
        'total_fee'    => '0.01',
        'body'         => '10.1新马泰三日游来回机票酒店住宿',
        'ip'               => '10.45.251.153',
    );
    $api = new Payment_Wxpay_Pay($config);
    $api->buildAppPayParam($param);

}


function testWapPay()
{

}

function testJsPay()
{

}

function testNativePay()
{

}