<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-08
 * Time: 09:50
 */

namespace Isliang\Thrift\Framework\Proxy;

use Isliang\Thrift\Framework\Config\LogConfig;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ThriftProxy
{
    protected $instance;
    private $classname;
    private static $logger;

    public function __construct($instance, $classname)
    {
        $this->instance = $instance;
        $this->classname = $classname;
        if (empty(self::$logger)) {
            self::$logger = new Logger('THRIFT-SERVICE');
            self::$logger->pushHandler(new StreamHandler(LogConfig::getLogFile(), Logger::INFO));
        }
    }

    public function __call($name, $arguments)
    {
        try {
            $start = microtime(true);
            self::$logger->info($this->classname . ".{$name}\tstart request");
            $result = call_user_func_array([$this->instance, $name], $arguments);
            $used_time = number_format((microtime(true) - $start)*1000,
                2, '.', '');
            self::$logger->info($this->classname . ".{$name}\treceive response\tused_time $used_time");
            return $result;
        } catch (\Exception $e) {
            self::$logger->warning($this->classname . ".$name error," . $e->getMessage() . ',' . $e->getMessage());
            throw $e;
        }
    }
}