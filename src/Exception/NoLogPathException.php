<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-12
 * Time: 10:20
 */

namespace Isliang\Thrift\Framework\Exception;

use Isliang\Thrift\Framework\Constant\ExceptionConst;

class NoLogPathException extends \Exception
{
    public function __construct()
    {
        parent::__construct("no log path set, use \Isliang\Thrift\Framework\Config::setLogPath to set",
            ExceptionConst::EXCEPTION_CODE_NO_LOG_PATH);
    }
}