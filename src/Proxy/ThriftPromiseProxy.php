<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-08
 * Time: 16:54
 */

namespace Isliang\Thrift\Framework\Proxy;

use Isliang\Thrift\Framework\Config\LogConfig;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Thrift\Exception\TApplicationException;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TMemoryBuffer;
use Thrift\Type\TMessageType;

class ThriftPromiseProxy
{
    /**
     * @var TGuzzleTransport
     */
    private $transport;

    private $classname;

    private static $logger;

    public function __construct($transport, $classname)
    {
        $this->transport = $transport;
        $this->classname = $classname;
        if (empty(self::$logger)) {
            self::$logger = new Logger('THRIFT-SERVICE');
            self::$logger->pushHandler(new StreamHandler(LogConfig::getLogFile(), Logger::INFO));
        }
    }

    //返回promise
    public function __call($name, $arguments)
    {
        $start = microtime(true);

        self::$logger->info($this->classname . ".{$name}\tstart request");
        $reflection = new \ReflectionMethod($this->classname . 'If', $name);
        $params = array_map(function ($p) {
            return $p->name;
        }, $reflection->getParameters());

        $args_name = $this->classname . '_' . $name . '_args';
        $result_name = $this->classname . '_' . $name . '_result';


        $argsObj = new $args_name(array_combine($params, $arguments));

        $protocol = new TBinaryProtocol($this->transport);

        $protocol->writeMessageBegin($name, TMessageType::CALL, 0);
        $argsObj->write($protocol);
        $protocol->writeMessageEnd();

        $promise = $this->transport->async();

        $promise = $promise->then(
            function ($response) use ($result_name) {
                $body = '' . $response->getBody();
                $rseqid = 0;
                $fname = null;
                $mtype = 0;
                $input = new TBinaryProtocol(new TMemoryBuffer($body));
                if (function_exists('thrift_protocol_read_binary')) {
                    $result = thrift_protocol_read_binary($input, $result_name, $input->isStrictRead());
                } else {
                    $input->readMessageBegin($fname, $mtype, $rseqid);
                    if ($mtype == TMessageType::EXCEPTION) {
                        $x = new TApplicationException();
                        $x->read($input);
                        $input->readMessageEnd();
                        throw $x;
                    }

                    $result = new $result_name;
                    $result->read($input);
                    $input->readMessageEnd();
                }
                if ($result->success !== null) {
                    return $result->success;
                } else {
                    throw new \Exception(" failed: unknown result");
                }
            }
        );

        //记录请求结束日志
        $promise->then(
            function ($value) use ($name, $start) {
                $used_time = number_format((microtime(true) - $start)*1000,
                    2, '.', '');
                self::$logger->info($this->classname . ".{$name}\treceive response\tused_time $used_time");
                return $value;
            }
        );

        return $promise;
    }
}