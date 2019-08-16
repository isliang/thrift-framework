<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-16
 * Time: 09:44
 */
namespace Isliang\Thrift\Framework\Exception;

use Isliang\Thrift\Framework\Constant\ExceptionConst;

class RegisterConfigErrorException extends \Exception
{
    public function __construct($var)
    {
        parent::__construct("register config error {$var}",
            ExceptionConst::EXCEPTION_REGISTER_CONFIG_ERROR);
    }
}