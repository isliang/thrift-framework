<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 14:03
 */

namespace Isliang\Thrift\Framework\Response;

class SwooleResponse implements Response
{
    /**
     * @var \Swoole\Http\Response
     */
    private $swoole_response;

    /**
     * FpmResponse constructor.
     * @param $response \Swoole\Http\Response
     */
    public function __construct($response)
    {
        $this->swoole_response = $response;
    }

    public function header(string $key, string $value)
    {
        $this->swoole_response->header($key, $value);
    }

    public function status(int $http_status_code)
    {
        $this->swoole_response->status($http_status_code);
    }

    public function write(string $data)
    {
        $this->swoole_response->write($data);
    }

    public function end()
    {
        $this->swoole_response->end();
    }
}