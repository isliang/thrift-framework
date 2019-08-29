<?php
/**
 * User: isliang
 * Date: 2019/8/29
 * Time: 16:37
 * Email: wslhdu@163.com
 **/
namespace Isliang\Thrift\Framework\Exception;

use Isliang\Thrift\Framework\Constant\ExceptionConst;

class RegisterServerException extends \Exception
{
    public function __construct()
    {
        parent::__construct("no usable register server url",
            ExceptionConst::EXCEPTION_REGISTER_SERVER_ERROR);
    }
}