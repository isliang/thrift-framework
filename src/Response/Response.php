<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 14:02
 */

namespace Isliang\Thrift\Framework\Response;

interface Response
{
    /**
     * @param string $key
     * @param string $value
     * @return mixed
     * 设置header
     */
    public function header(string $key, string $value);

    /**
     * @param int $http_status_code
     * @return mixed
     * 设置http code
     */
    public function status(int $http_status_code);

    /**
     * @param string $data
     * @return mixed
     * 启用Http Chunk分段向浏览器发送相应内容
     */
    public function write(string $data);

    /**
     * @return mixed
     * 发送Http响应体，并结束请求处理
     */
    public function end();
}