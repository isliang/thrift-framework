<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 14:03
 */

namespace Isliang\Thrift\Framework\Request;

class SwooleRequest implements Request
{
    /**
     * @var \Swoole\Http\Request
     */
    private $swoole_request;
    /**
     * SwooleRequest constructor.
     * @param $request \Swoole\Http\Request
     */
    public function __construct($request)
    {
        $this->swoole_request = $request;
    }

    public function get($key)
    {
        return $this->swoole_request->get[$key] ?? null;
    }

    public function post($key)
    {
        return $this->swoole_request->post[$key] ?? null;
    }

    public function server($key)
    {
        return $this->swoole_request->server[$key] ?? null;
    }

    public function content()
    {
        return $this->swoole_request->rawContent() ?? null;
    }
}