<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 09:16
 */

namespace Isliang\Thrift\Framework\Controller;

use Isliang\Thrift\Framework\Registration\Register;
use Swoole\Http\Request;
use Swoole\Http\Response;

class SwooleServerController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * 节点下线
     */
    public function shutdown()
    {
        Register::unRegister();
    }

    /**
     * ping节点
     */
    public function ping()
    {
        $this->response->end('pong');
    }
}