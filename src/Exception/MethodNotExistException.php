<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 09:24
 */

namespace Isliang\Thrift\Framework\Exception;

use Isliang\Thrift\Framework\Constant\ExceptionConst;

class MethodNotExistException extends \Exception
{
    public function __construct($class, $method)
    {
        parent::__construct("{$class}#{$method} not exist",
            ExceptionConst::EXCEPTION_METHOD_NOT_EXIST);
    }
}