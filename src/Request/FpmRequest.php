<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 14:03
 */

namespace Isliang\Thrift\Framework\Request;

class FpmRequest implements Request
{
    public function get($key)
    {
        return $_GET[$key] ?? null;
    }

    public function post($key)
    {
        return $_POST[$key] ?? null;
    }

    public function server($key)
    {
        return strtolower($_SERVER[$key]) ?? null;
    }

    public function content()
    {
        return file_get_contents('php://input') ?? null;
    }

}