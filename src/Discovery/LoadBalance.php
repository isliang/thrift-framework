<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-20
 * Time: 09:13
 */
namespace Isliang\Thrift\Framework\Discovery;

class LoadBalance
{
    private static $endpoint_list = [];
    private static $index_list = [];

    public static function init($endpoint_list)
    {
        foreach ($endpoint_list as $service_name => $value) {
            self::$endpoint_list[$service_name] = self::getEndpointListWithWeight($value);
        }
    }

    private static function getEndpointListWithWeight($endpoints)
    {
        $list = [];
        foreach ($endpoints as $endpoint => $weight) {
            $tmp = array_fill(0, $weight, $endpoint);
            $list = array_merge($list, $tmp);
        }
        shuffle($list);
        return $list;
    }

    public static function next($service_name)
    {
        if (isset(self::$index_list[$service_name])) {
            self::$index_list[$service_name]++;
            if (self::$index_list[$service_name] >= count(self::$endpoint_list[$service_name])) {
                self::$index_list[$service_name] = 0;
            }
        } else {
            self::$index_list[$service_name] = 0;
        }
        return self::$endpoint_list[$service_name][self::$index_list[$service_name]];
    }
}