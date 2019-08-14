<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 11:09
 */

namespace Isliang\Thrift\Framework\Transport;

use Isliang\Thrift\Framework\Request\Request;
use Isliang\Thrift\Framework\Response\Response;
use Thrift\Transport\TTransport;
use Thrift\Transport\TTransportException;

class TSwooleTransport extends TTransport
{
    /**
     * @var Response
     */
    private $response;

    private $buf;

    private $write_buf = '';

    /**
     * TSwooleTransport constructor.
     * @param $request Request
     * @param $response Response
     */
    public function __construct($request, $response)
    {
        $this->response = $response;
        $this->setBuf($request->content());
    }

    private function setBuf($buf)
    {
        $this->buf = fopen('php://memory', 'rw+');
        fputs($this->buf, $buf);
        rewind($this->buf);
    }

    public function isOpen()
    {
        return true;
    }

    public function open()
    {
    }

    public function close()
    {
        if ($this->buf != null) {
            fclose($this->buf);
            $this->buf = null;
        }
    }

    public function read($len)
    {
        return fread($this->buf, $len);
    }

    public function write($buf)
    {
        $this->write_buf .= $buf;
    }

    public function flush()
    {
        $this->response->write($this->write_buf);
        $this->write_buf = '';
    }
}