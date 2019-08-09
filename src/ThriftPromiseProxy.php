<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-08
 * Time: 16:54
 */

namespace Isliang\Thrift\Framework;

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

    private $module_name;

    public function __construct($transport, $module_name)
    {
        $this->transport = $transport;
        $this->module_name = $module_name;
    }

    //返回promise
    public function __call($name, $arguments)
    {
        $reflection = new \ReflectionMethod($this->module_name . 'If', $name);
        $params = array_map(function ($p) {
            return $p->name;
        }, $reflection->getParameters());

        $args_name = $this->module_name . '_' . $name . '_args';
        $result_name = $this->module_name . '_' . $name . '_result';


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

        return $promise;
    }
}