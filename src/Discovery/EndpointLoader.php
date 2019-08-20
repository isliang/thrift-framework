<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-16
 * Time: 16:04
 */

namespace Isliang\Thrift\Framework\Discovery;

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

    public function __construct($endpoint_config_file)
    {
        if (file_exists($endpoint_config_file) && is_readable($endpoint_config_file)) {
            $this->endpoint_config_file = $endpoint_config_file;
            $this->md5 = md5_file($endpoint_config_file);
        } else {
            throw new FileNotExistException($endpoint_config_file);
        }
    }

    /**
     * @param $service_name string 小写 多个单词中划线分隔
     * @return string|null
     */
    public function getEndpoint($service_name)
    {
        $this->loadEndpointList();
        if (!empty($this->endpoint_list[$service_name])) {
            return LoadBalance::next($service_name);
        } else {
            return null;
        }
    }

    private function loadEndpointList()
    {
        $md5 = md5_file($this->endpoint_config_file);
        if (empty($this->endpoint_list) || $this->md5 != $md5) {
            $this->md5 = $md5;
            $file_type = pathinfo($this->endpoint_config_file)['extension'];
            if ($file_type == 'php') {
                //php file
                //$config[$service-name] = ['scheme'=> '','host' => '', 'port' => '', 'weight' => '']
                $endpoint_list = require_once $this->endpoint_config_file;
            } else {
                //json file
                $endpoint_list = json_decode(file_get_contents($this->endpoint_config_file), true);
            }
            foreach ($endpoint_list as $service_name => $value) {
                foreach ($value as $v) {
                    $this->endpoint_list[$service_name]["{$v['scheme']}://{$v['host']}:{$v['port']}"] = $v['weight'];
                }
            }
            LoadBalance::init($this->endpoint_list);
        }
    }
}