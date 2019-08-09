<?php
namespace Isliang\Thrift\Framework;

use Thrift\Transport\TTransport;

class TFastMemoryBuffer extends TTransport
{

    protected $buf = null;

    public function __construct($buf)
    {
        $this->buf = fopen('php://memory', 'rw+');
        fputs($this->buf, $buf);
        rewind($this->buf);
    }

    public function isOpen()
    {
        return $this->buf != null;
    }

    public function open()
    {}

    public function close()
    {
        if ($this->buf != null) {
            fclose($this->buf);
            $this->buf = null;
        }
    }

    public function write($buf)
    {
        throw new Exception('not support yet');
    }

    public function putBack($data)
    {
        throw new Exception('not support yet');
    }

    public function read($len)
    {
        return fread($this->buf, $len);
    }

}
