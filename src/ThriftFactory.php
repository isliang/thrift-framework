<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-08
 * Time: 09:31
 */

namespace Isliang\Thrift\Framework;

use Isliang\Thrift\Framework\Discovery\EndpointLoader;
use Isliang\Thrift\Framework\Proxy\ThriftPromiseProxy;
use Isliang\Thrift\Framework\Proxy\ThriftProxy;
use Isliang\Thrift\Framework\Transport\TGuzzleTransport;
use Thrift\Transport\TCurlClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TBinaryProtocol;

class ThriftFactory
{
    private static $sync_service = [];
    private static $async_service = [];
    /**
     * @var EndpointLoader
     */
    private static $endpoint_loader;

    private static function init()
    {
        if (!self::$endpoint_loader) {
            global $config;
            if (empty($config['endpoint_config_file'])) {
                throw new NoEndpointConfigError();
            }
            self::$endpoint_loader = new EndpointLoader($config['endpoint_config_file']);
        }
    }
    /**
     * @param $classname
     * @return ThriftProxy
     * \service\order\ListServiceIf => /service-order/listService
     */
    public static function getService($classname)
    {
        if ('If' === substr($classname, -2)) {
            $classname = substr($classname, 0, -2);
        }

        if (!empty(self::$sync_service[$classname])) {
            return self::$sync_service[$classname];
        }
        self::init();
        list($service_name, $uri) = self::buildUri($classname);
        $endpoint = self::$endpoint_loader->getEndpoint($service_name);

        $client_name = $classname . 'Client';
        $socket = new TCurlClient($endpoint['host'], $endpoint['port'], $uri, $endpoint['scheme']);
        $transport = new TBufferedTransport($socket, 1024, 1024);
        $protocol = new TBinaryProtocol($transport, true, true);
        $client = new $client_name($protocol);
        $proxy = new ThriftProxy($client, $classname);

        self::$sync_service[$classname] = $proxy;

        return $proxy;
    }


    /**
     * @param $classname
     * @return ThriftPromiseProxy|mixed
     * 使用guzzle，支持异步
     */
    public static function getAsyncService($classname)
    {
        if ('If' === substr($classname, -2)) {
            $classname = substr($classname, 0, -2);
        }

        if (!empty(self::$async_service[$classname])) {
            return self::$async_service[$classname];
        }
        self::init();
        list($service_name, $uri) = self::buildUri($classname);
        $endpoint = self::$endpoint_loader->getEndpoint($service_name);

        $socket = new TGuzzleTransport($endpoint['host'], $endpoint['port'], $uri, $endpoint['scheme']);
        $proxy = new ThriftPromiseProxy($socket, $classname);

        self::$async_service[$classname] = $proxy;

        return $proxy;
    }

    /**
     * @param $classname
     * @return array
     * Service\Order\ListServiceImpl => service-order/listService
     */
    private static function buildUri($classname)
    {
        $class_arr = explode('\\', trim($classname, '\\'));
        $module_name = lcfirst(end($class_arr));
        $class_arr = array_slice($class_arr, 0, -1);
        $service_name = strtolower(implode('-', $class_arr));
        $uri = '/' . $service_name . '/' . $module_name;

        return [$service_name, $uri];
    }
}