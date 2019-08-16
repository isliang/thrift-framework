<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-12
 * Time: 16:38
 * 服务注册 启动入口
 */

$loader = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($loader)) {
    $loader = __DIR__ . '/../../../autoload.php';
}

require_once $loader;


$config_file = '/data1/www/htdocs/config/thrift-service.php';

if (!file_exists($config_file)) {
    $config_file = __DIR__ . '/config.php';
}

$config = require_once $config_file;

use \Isliang\Thrift\Framework\Config\RegisterConfig;
use \Isliang\Thrift\Framework\ThriftHttpServer;

$reg_config = new RegisterConfig($config);

$server = new ThriftHttpServer($reg_config);

$server->start();