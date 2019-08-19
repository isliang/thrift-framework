<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-16
 * Time: 15:42
 */

$loader = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($loader)) {
    $loader = __DIR__ . '/../../../autoload.php';
}

require_once $loader;

use \Isliang\Thrift\Framework\Discovery\EndpointDiscovery;


$discovery = new EndpointDiscovery(
    'http://127.0.0.1:2379',
    '/data1/www/htdocs/config/nodes.json',
    'dev'
);

$discovery->getServiceNodes();