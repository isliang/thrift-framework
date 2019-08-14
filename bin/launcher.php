<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-12
 * Time: 16:38
 */

require_once dirname(__DIR__) . "/autoload.php";
use \Isliang\Thrift\Framework\Config\RegisterConfig;
use \Isliang\Thrift\Framework\ThriftHttpServer;

$reg_config = new RegisterConfig();
$reg_config->setRegisterUrl('http://127.0.0.1:2379')
    ->setHost('127.0.0.1')
    ->setPort(80)
    ->setEnv('dev')
    ->setServiceName('service-order')
    ->setWeight(1)
    ->setScheme('http');

$server = new ThriftHttpServer($reg_config);

$server->start();