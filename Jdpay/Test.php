<?php
/**
 * @description: 京东支付测试程序
 * 可以将该示例程序放置于nginx/apache虚拟目录下，通过URL中platform参数来测试不同平台支付接口
 * @author: luerfeng
 * @time: 15/10/9-下午4:07
 * @version: 1.0
 */

namespace Payment\Jdpay;

include 'Api.php';
include 'Config.php';
include 'Pay.php';

$platform = $_GET['platform'];

switch($platform)
{
    case 'pc':
        testPcPay();
        break;
    case 'wap':
        testWapPay();
        break;
    case 'app':
        testAppPay();
        break;
    default:
        echo 'undefined platform',PHP_EOL;
}


function testPcPay()
{
    $config = array_merge(Payment_Jdpay_Config::$platformConfig['pc'], Payment_Jdpay_Config::$sellerConfig);
    $param = array(
        'notify_url'   => 'http://www.mafengwo.cn/alipay/notify_url.php',
        'return_url'   => 'http://www.baidu.com',
        'out_trade_no' => '1514454139936301',
        'subject'      => 'xxx三日游',
        'total_fee'    => '0.01',
        'body'         => '10.1新马泰三日游来回机票酒店住宿',
        'ip'           => '10.45.251.153',
    );

    $api = new Payment_Jdpay_Pay($config);
    $html = $api->buildPcPayForm($param);
    echo $html;
}


function testWapPay()
{
    $config = array_merge(Payment_Jdpay_Config::$platformConfig['wap'], Payment_Jdpay_Config::$sellerConfig);
    $param = array(
        'notify_url'       => 'http://www.mafengwo.cn/alipay/notify_url.php',
        'return_url'       => 'http://www.baidu.com',
        'error_notify_url' => 'http://www.mafengwo.com/error.php',
        'out_trade_no'     => '1514454139936301',
        'subject'          => 'xxx三日游',
        'total_fee'        => '0.01',
        'body'             => '10.1新马泰三日游来回机票酒店住宿',
        'ip'               => '10.45.251.153',
    );

    $api = new Payment_Jdpay_Pay($config);
    $html = $api->buildWapPayForm($param);
    echo $html;
}

function testAppPay()
{

}
