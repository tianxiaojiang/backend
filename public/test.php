<?php
/**
 * 支付宝app支付参数拦截
 * User: tianweimin
 * Date: 2018/3/27
 * Time: 11:24
 */

header('Content-Type: text/json; charset=UTF-8');

$url = 'pay://sdk.install.mobpay.ztgame.com/?arg=';
$arg = base64_encode('download='.urlencode('https://itunes.apple.com/cn/app/zhi-fu-bao-qian-bao-qiang/id333206289?mt=8').'&schemename=alipay.dut&alipaystr=' . base64_encode('app_id=2016063001569951&method=alipay.trade.app.pay&format=JSON&charset=UTF-8&sign_type=RSA2&timestamp=2018-09-18+15%3A58%3A54&version=1.0&notify_url=http%3A%2F%2F120.92.188.97%3A8091%2Fresponse%2Falipayheyhey&biz_content=%7B%22body%22%3A%22%5Cu83b7%5Cu53d6%5Cu91d1%5Cu8611%5Cu83c7%3A80%5Cu4e2a%5Cu652f%5Cu4ed80.01%5Cu5143%22%2C%22subject%22%3A%22%5Cu83b7%5Cu53d6%5Cu91d1%5Cu8611%5Cu83c7%3A80%5Cu4e2a%5Cu652f%5Cu4ed80.01%5Cu5143%22%2C%22out_trade_no%22%3A%22qq15372575348372233%22%2C%22total_amount%22%3A%220.01%22%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%7D&sign=Zf26mDh4ePiAF7TUVvHP%2F6b2PY1fsAlxwOead70OI58Dd8k4%2FYHGRKPrMWHA8eFyXNcF1EApaJrjVpEjRSZV%2Bv66i8yY%2BoD6%2BwoxtdNPK%2FIVZkd0NmhR8byp%2BX0dTXotH1FMwO9b4gRu7RC6jqZ2hhsUN0oriZJANY1QIgy3itGn8MD46Fnbj2HG0NWTCT7qo0gyxrnRdf1B4GkwC1Y%2FJVXy%2BOi9rhNcf5Hvmqqn4YY4IQ3qJZKUqdvPcCyHyNV3T19u9dczqw6ySwxKnSFVfEDygmLl2qc4sf0ibWDGq8hbcnA%2BV3SooFwK6bjm%2F43nBxBZa3kh8gQ9zXOiHYe3og%3D%3D'));

$res = [
    "code" => 1,
    "msg" => "success",
    "data" => $url . $arg
];

echo json_encode($res);