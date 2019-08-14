<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 14:04
 */

namespace Isliang\Thrift\Framework\Response;

class ResponseFactory
{
    public static function getResponse($response)
    {
        if ('fpm-fcgi' == php_sapi_name()) {
            return new FpmResponse();
        } else {
            return new SwooleResponse($response);
        }
    }
}