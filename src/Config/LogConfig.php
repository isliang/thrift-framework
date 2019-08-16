<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-12
 * Time: 15:08
 */


namespace Isliang\Thrift\Framework\Config;

class LogConfig
{
    /**
     * @var string 日志存放目录
     */
    private static $log_path;

    private static $log_file;

    const LOG_FILE_NAME_FORMAT = 'thrift.log.%s';

    /**
     * @return string
     * @throws NoLogPathException
     */
    private static function getLogPath(): string
    {
        if (empty(self::$log_path)) {
            throw new NoLogPathException();
        }
        return self::$log_path;
    }

    /**
     * @param string $log_path
     */
    public static function setLogPath(string $log_path)
    {
        self::$log_path = $log_path;
    }

    /**
     * @return string
     * @throws NoLogPathException
     */
    public static function getLogFile()
    {
        if (empty(self::$log_file)) {
            self::$log_file = sprintf(self::LOG_FILE_NAME_FORMAT, date('Ymd'));
        }
        return rtrim(self::getLogPath(), '/') . '/'. trim(self::$log_file, '/');
    }
}