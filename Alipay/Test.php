<?php
/**
 * @description:
 * @author: luerfeng
 * @time: 15/10/8-下午3:16
 * @version: 1.0
 */

namespace Payment\Alipay;

include 'Api.php';
include 'Config.php';
include 'Pay.php';


$platform = $_GET['platform'];


switch($platform)
{
    case 'pc':
        testPcPay();
        break;
    case 'mobile':
        testMobilePay();
        break;
    case 'app':
        testAppPay();
        break;
    default:
        echo 'undefined platform',PHP_EOL;
}


function testPcPay()
{
    $config = array_merge(Payment_Alipay_Config::$platformConfig, Payment_Alipay_Config::$sellerConfig);
    $param = array(
        'notify_url' => 'http://payitf.mafengwo.cn/alipay/notify_url.php',
        'return_url' => 'http://www.baidu.com',
        'error_notify_url' => 'http://www.mafengwo.cn/error.php',
        'out_trade_no' => '1514454139936301',
        'subject' => 'xxx三日游',
        'total_fee' => '0.99',
        'body' => '10.1新马泰三日游，来回机票＋酒店住宿',
        'show_url' => 'http://www.mafengwo.cn',
        'it_b_pay' => '30m',
    );

    $api = new Payment_Alipay_Pay($config);
    $html = $api->buildPcPayForm($param);
    echo $html;
}


function testMobilePay()
{

}

function testAppPay()
{

}