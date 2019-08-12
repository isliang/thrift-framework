<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-12
 * Time: 10:20
 */

namespace Isliang\Thrift\Framework\Exception;

class NoLogPathException extends \Exception
{
    public function __construct()
    {
        parent::__construct("no log path set, use \Isliang\Thrift\Framework\Config::setLogPath to set", Constant::EXCEPTION_CODE_NOLOGPATH);
    }
}