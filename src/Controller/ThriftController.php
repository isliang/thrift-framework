<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 09:51
 */

namespace Isliang\Thrift\Framework\Controller;

use Isliang\Thrift\Framework\Request\Request;
use Isliang\Thrift\Framework\Request\RequestFactory;
use Isliang\Thrift\Framework\Response\Response;
use Isliang\Thrift\Framework\Response\ResponseFactory;
use Isliang\Thrift\Framework\ThriftServiceLauncher;

class ThriftController
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
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
        $this->request = RequestFactory::getRequest($request);
        $this->response = ResponseFactory::getResponse($response);
        $this->launcher = new ThriftServiceLauncher();
    }

    public function handle()
    {
        $this->launcher->handle($this->request, $this->response);
    }
}