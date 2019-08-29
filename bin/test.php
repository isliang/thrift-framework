<?php
/**
 * User: isliang
 * Date: 2019/8/29
 * Time: 14:37
 * Email: wslhdu@163.com
 **/
$loader = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($loader)) {
    $loader = __DIR__ . '/../../../autoload.php';
}

require_once $loader;

$endpoint = [
    'service-order' => [
        'http://1:80' => 1,
        'http://2:80' => 2,
        'http://3:80' => 3,
        'http://4:80' => 4,
    ]
];
\Isliang\Thrift\Framework\Discovery\LoadBalance::init($endpoint);

$count = 0;
while(true) {
    $res = \Isliang\Thrift\Framework\Discovery\LoadBalance::next('service-order');
    print_r($res);
    echo "\n";
    $count++;
    if ($count >= 2000) {
        break;
    }
}