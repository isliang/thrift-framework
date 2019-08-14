<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-13
 * Time: 17:36
 */

namespace Isliang\Thrift\Framework\Router;

use FastRoute;
use Isliang\Thrift\Framework\Controller\ThriftController;
use Isliang\Thrift\Framework\Exception\ClassNotFoundException;
use Isliang\Thrift\Framework\Exception\MethodNotExistException;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Router
{
    /**
     * @param $request Request
     * @param $response Response
     * @throws ClassNotFoundException
     * @throws MethodNotExistException
     */
    public static function dispatch($request, $response)
    {
        $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
            $r->addRoute(['GET', 'POST'], '/shutdown',
                '\Isliang\Thrift\Framework\Controller\SwooleServerController#shutdown'
            );
            $r->addRoute(['GET', 'POST'], '/ping',
                '\Isliang\Thrift\Framework\Controller\SwooleServerController#ping'
            );
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::FOUND:
                list($class, $handler) = explode('#',$routeInfo[1]);
                if (!class_exists($class)) {
                    throw new ClassNotFoundException($class);
                }
                if (!method_exists($class, $handler)) {
                    throw new MethodNotExistException($class, $handler);
                }
                call_user_func_array([new $class($request, $response), $handler], []);
                break;
            default:
                call_user_func_array([new ThriftController($request, $response), 'handle'], []);
        }
    }
}