<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-13
 * Time: 14:04
 */

namespace Isliang\Thrift\Framework;

use Isliang\Thrift\Framework\Config\RegisterConfig;
use Isliang\Thrift\Framework\Registration\Register;
use Isliang\Thrift\Framework\Registration\ServiceNode;
use Isliang\Thrift\Framework\Router\Router;
use Swoole\Http\Request;

class ThriftHttpServer
{
    /**
     * @var \Swoole\Http\Server
     */
    private static $server;

    /**
     * ThriftHttpServer constructor.
     * @param $register_config RegisterConfig
     */
    public function __construct($register_config)
    {
        self::$server = new \Swoole\Http\Server("127.0.0.1", 9501);
        self::$server->on('request', [$this, 'onRequest']);
        Register::register($register_config->getRegisterUrl(),
            new ServiceNode(
                $register_config->getServiceName(),
                $register_config->getHost(),
                $register_config->getPort(),
                $register_config->getEnv(),
                $register_config->getWeight(),
                $register_config->getScheme()
            )
        );
    }

    public static function getServer()
    {
        return self::$server;
    }

    public function onRequest($request, $response)
    {
        $this->setServer($request);
        Router::dispatch($request, $response);
    }

    public function start()
    {
        self::$server->start();
    }

    /**
     * @param $request Request
     */
    public function setServer($request)
    {
        $_SERVER['REQUEST_METHOD'] = $request->server['request_method'];
        $_SERVER['REQUEST_URI'] = $request->server['request_uri'];
    }
}