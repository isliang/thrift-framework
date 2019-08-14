<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 14:03
 */

namespace Isliang\Thrift\Framework\Response;

class FpmResponse implements Response
{
    public function header(string $key, string $value)
    {
        header($key, $value);
    }

    public function status(int $http_status_code)
    {
        http_send_status($http_status_code);
    }

    public function write(string $data)
    {
        $fp = @fopen('php://output', 'w');
        fputs($fp, $data);
        fclose($fp);
    }

    public function end()
    {
        fastcgi_finish_request();
    }

}