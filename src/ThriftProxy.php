<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-08
 * Time: 09:50
 */

namespace Isliang\Thrift\Framework;

class ThriftProxy
{
    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->instance, $name], $arguments);
    }
}