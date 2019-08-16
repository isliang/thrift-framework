<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 09:24
 */

namespace Isliang\Thrift\Framework\Exception;

use Isliang\Thrift\Framework\Constant\ExceptionConst;

class ClassNotFoundException extends \Exception
{
    public function __construct($class)
    {
        parent::__construct("{$class} class not found",
            ExceptionConst::EXCEPTION_CLASS_NOT_FOUND);
    }
}