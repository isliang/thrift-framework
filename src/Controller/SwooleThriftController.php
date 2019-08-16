<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 09:51
 */

namespace Isliang\Thrift\Framework\Controller;

use Isliang\Thrift\Framework\ThriftServiceLauncher;

class SwooleThriftController
{
    /**
     * @var \Swoole\Http\Request
     */
    private $request;

    /**
     * @var \Swoole\Http\Response
     */
    private $response;

    /**
     * @var ThriftServiceLauncher
     */
    private $launcher;

    /**
     * ThriftController constructor.
     * @param $request \Swoole\Http\Request
     * @param $response \Swoole\Http\Response
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->launcher = new ThriftServiceLauncher();
    }

    public function handle()
    {
        $this->launcher->handle($this->request, $this->response);
    }
}