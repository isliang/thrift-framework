<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-07
 * Time: 14:22
 */
namespace Isliang\Thrift\Framework;

use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TPhpStream;
use Thrift\Protocol\TBinaryProtocol;

class ThriftServiceLauncher
{
    public function run()
    {
        //根据url匹配service name，module name
        //URI规则：{domain}/{service_name}/{module_name}
        $uri = $_SERVER['REQUEST_URI'];
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        ///service-order/list => ServiceOrderListImpl
        list(, $service_name, $module_name) = explode('/', $uri);
        $namespace = preg_replace('/-/', '\\', $service_name) . '\\';
        $impl = $namespace . preg_replace(
            '/-/',
            '',
            preg_replace_callback('/([a-zA-Z]+)/', function ($matches) {
                    return ucfirst($matches[0]);
            }, $service_name)
        ) . ucfirst($module_name) . 'Impl';

        try {
            $processor = new ThriftServiceProcessor(new $impl(), $namespace . ucfirst($module_name));
            $transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
            $protocol = new TBinaryProtocol($transport, true, true);
            $transport->open();
            $processor->process($protocol, $protocol);
            $transport->close();
        } catch (\Exception $e) {
        } finally {
        }
    }
}
