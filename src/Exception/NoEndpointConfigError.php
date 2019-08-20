<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-20
 * Time: 15:23
 */

namespace Isliang\Thrift\Framework\Exception;

use Isliang\Thrift\Framework\Constant\ExceptionConst;

class NoEndpointConfigError extends \Exception
{
    public function __construct()
    {
        parent::__construct("no endpoint config file",
            ExceptionConst::EXCEPTION_NO_ENDPOINT_CONFIG);
    }
}