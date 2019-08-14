<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-07
 * Time: 14:22
 */
namespace Isliang\Thrift\Framework;

use Isliang\Thrift\Framework\Request\RequestFactory;
use Isliang\Thrift\Framework\Response\ResponseFactory;
use Isliang\Thrift\Framework\Transport\TSwooleTransport;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TBinaryProtocol;

class ThriftServiceLauncher
{
    /**
     * @param $req Request
     * @param $resp Response
     */
    public function handle($req, $resp)
    {
        $request = RequestFactory::getRequest($req);
        $response = ResponseFactory::getResponse($resp);

        list($impl, $namespace, $module_name) = $this->getHandleClass();
        $processor = new ThriftServiceProcessor(new $impl(), $namespace . ucfirst($module_name));
        $transport = new TBufferedTransport(new TSwooleTransport($request, $response));
        $protocol = new TBinaryProtocol($transport, true, true);
        $transport->open();
        $processor->process($protocol, $protocol);
        $transport->close();
    }

    private function getHandleClass()
    {
        //根据url匹配service name，module name
        //URI规则：{domain}/{service_name}/{module_name}
        $uri = $_SERVER['REQUEST_URI'];
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        ///service-order/listService => service\order\ListServiceImpl
        list(, $service_name, $module_name) = explode('/', $uri);
        $namespace = preg_replace('/-/', '\\', $service_name) . '\\';
        $impl = $namespace . ucfirst($module_name) . 'Impl';

        return [
            $impl,
            $namespace,
            $module_name
        ];
    }
}
