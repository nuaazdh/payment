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

ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 2048);

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
        'body' => '10.1新马泰三日游来回机票酒店住宿',
        'show_url' => 'http://www.mafengwo.cn',
        'it_b_pay' => '30m',
    );

    $api = new Payment_Alipay_Pay($config);
    // 返回请求表单
    $html = $api->buildPcPayForm($param);
//    echo $html;
    // 返回请求URL
    $url = sprintf('%s?%s',
        $config['pay_url'],
        $api->buildPcPayUrl($param));
//    header('Location:'.$url);
}


function testMobilePay()
{
    $config = array_merge(Payment_Alipay_Config::$platformConfig, Payment_Alipay_Config::$sellerConfig);
    $config['sign_type'] = strtoupper('RSA');
    $param = array(
        'notify_url' => 'http://payitf.mafengwo.cn/alipay/notify_url.php',
        'return_url' => 'http://www.baidu.com',
        'error_notify_url' => 'http://www.mafengwo.cn/error.php',
        'out_trade_no' => '1512460346019990',
        'subject' => 'xxx三日游',
        'total_fee' => '0.01',
        'body' => '10.1新马泰三日游来回机票酒店住宿',
        'show_url' => 'http://www.mafengwo.cn/sales/306255.html',
        'it_b_pay' => '30m',
    );
    $api = new Payment_Alipay_Pay($config);
    // 返回请求form
    $html = $api->buildWapPayForm($param);
    echo $html;
    // 返回请求的URL
    $url = $api->buildWapPayUrl($param);
//    header("Location:$url");
}

function testAppPay()
{

}