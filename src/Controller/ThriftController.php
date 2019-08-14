<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 09:51
 */

namespace Isliang\Thrift\Framework\Controller;

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