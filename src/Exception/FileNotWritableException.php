<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-16
 * Time: 15:52
 */
namespace Isliang\Thrift\Framework\Exception;

use Isliang\Thrift\Framework\Constant\ExceptionConst;

class FileNotWritableException extends \Exception
{
    public function __construct($file)
    {
        parent::__construct("{$file} not writable",
            ExceptionConst::FILE_NOT_WRITABLE);
    }
}