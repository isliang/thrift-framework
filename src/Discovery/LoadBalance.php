<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-20
 * Time: 09:13
 */
namespace Isliang\Thrift\Framework\Discovery;

use Robin\WeightedRobin;

class LoadBalance
{
    /**
     * @var WeightedRobin[]
     */
    private static $load_balance;

    public static function init($endpoint_list)
    {
        foreach ($endpoint_list as $service_name => $value) {
            $load_balance = new WeightedRobin();
            $load_balance->init($value);
            self::$load_balance[$service_name] = $load_balance;
        }
    }

    public static function next($service_name)
    {
        return parse_url(self::$load_balance[$service_name]->next());
    }
}