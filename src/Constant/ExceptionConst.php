<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-12
 * Time: 15:04
 */

namespace Isliang\Thrift\Framework\Constant;

class ExceptionConst
{
    /**
     * 没有定义日志路径 的异常 code
     */
    const EXCEPTION_CODE_NO_LOG_PATH = 1001;

    /**
     * 类不存在
     */
    const EXCEPTION_CLASS_NOT_FOUND = 1002;

    /**
     * 方法不存在
     */
    const EXCEPTION_METHOD_NOT_EXIST = 1003;

    /**
     * 节点注册配置有误
     */
    const EXCEPTION_REGISTER_CONFIG_ERROR = 1004;

    /**
     * 文件没有写权限
     */
    const EXCEPTION_FILE_NOT_WRITABLE = 1005;

    /**
     * 文件不存在或没有读权限
     */
    const EXCEPTION_FILE_NOT_EXIST = 1006;

    /**
     * 服务端节点配置文件不存在 
     */
    const EXCEPTION_NO_ENDPOINT_CONFIG = 1007;

    /**
     * 没有可用的服务注册中心
     */
    const EXCEPTION_REGISTER_SERVER_ERROR = 1008;
}