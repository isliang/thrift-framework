<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-16
 * Time: 16:04
 */

namespace Isliang\Thrift\Framework\Discovery;

use Robin\WeightedRobin;

class EndpointLoader
{
    /**
     * @var string 服务节点配置文件
     */
    private $endpoint_config_file;

    /**
     * @var 服务节点配置文件的md5值
     */
    private $md5;

    /**
     * @var array 节点列表
     */
    private $endpoint_list;

    /**
     * @var WeightedRobin
     */
    private static $load_balance;

    public function __construct($endpoint_config_file)
    {
        if (file_exists($endpoint_config_file) && is_readable($endpoint_config_file)) {
            $this->endpoint_config_file = $endpoint_config_file;
            $this->md5 = md5_file($endpoint_config_file);
        } else {
            throw new FileNotExistException($endpoint_config_file);
        }
        self::$load_balance = new WeightedRobin();
    }

    public function getEndpoint($service_name)
    {
        $this->loadEndpointList();
        if (!empty($this->endpoint_list[$service_name])) {
            return self::$load_balance->next();
        } else {
            return null;
        }
    }

    private function loadEndpointList()
    {
        $md5 = md5_file($this->endpoint_config_file);
        if (empty($this->endpoint_list) || $this->md5 != $md5) {
            $this->md5 = $md5;
            $endpoint_list = json_decode(file_get_contents($this->endpoint_config_file), true);
            foreach ($endpoint_list as $service_name => $value) {
                foreach ($value as $v) {
                    $endpoint = new Endpoint();
                    $endpoint->setScheme($v['scheme'])
                        ->setHost($v['host'])
                        ->setPort($v['port'])
                        ->setWeight($v['weight']);
                    $this->endpoint_list[$service_name][$endpoint->__toString()] = $endpoint->getWeight();
                }
            }
            foreach ($this->endpoint_list as $service_name => $value) {
                self::$load_balance->init($this->endpoint_list);
            }
        }
    }
}