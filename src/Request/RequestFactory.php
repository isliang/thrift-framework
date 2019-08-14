<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 14:04
 */

namespace Isliang\Thrift\Framework\Request;

class RequestFactory
{
    public static function getRequest($request)
    {
        if ('fpm-fcgi' == php_sapi_name()) {
            return new FpmRequest();
        } else {
            return new SwooleRequest($request);
        }
    }
}