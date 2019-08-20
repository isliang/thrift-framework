<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-15
 * Time: 18:05
 */

return [
    'register_url' => 'http://192.168.199.234:2379',//服务注册中心地址
    'service' => [
        'host' => gethostbyname(gethostname()),//节点ip
        'port' => 80,//端口
        'scheme' => 'http',
        'env' => 'dev',//环境 dev-开发环境
        'service_name' => 'isliang-service-order',//服务 service name
        'weight' => (function() {
            $weight = 1;
            if (file_exists('/proc/cpuinfo')) {
                preg_match('/cpu cores\t: (\d+)/', file_get_contents('/proc/cpuinfo'), $match);
                if (!empty($match[1]) && is_int($match[1])) {
                    $weight = $match;
                }
            }
            return $weight;
        })(),//权重，初始化为cpu核心数
    ],
];