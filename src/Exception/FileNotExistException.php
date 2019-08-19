<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-16
 * Time: 16:07
 */

namespace Isliang\Thrift\Framework\Exception;

use Isliang\Thrift\Framework\Constant\ExceptionConst;

class FileNotExistException extends \Exception
{
    public function __construct($file)
    {
        parent::__construct("{$file} not exist",
            ExceptionConst::FILE_NOT_EXIST);
    }
}