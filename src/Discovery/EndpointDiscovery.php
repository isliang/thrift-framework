<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-16
 * Time: 15:00
 */
namespace Isliang\Thrift\Framework\Discovery;

use Isliang\Thrift\Framework\Constant\CommonConst;
use Isliang\Thrift\Framework\Exception\FileNotWritableException;
use LinkORB\Component\Etcd\Client;

class EndpointDiscovery
{
    /**
     * @var Client
     */
    private $etcd_client;
    /**
     * @var array
     */
    private $node_list = [];

    private $timer_id;

    /**
     * @var string
     */
    private $env;

    /**
     * @var int
     */
    private $stime;

    /**
     * @var string 节点配置文件名
     */
    private $config_file;

    public function __construct($etcd_server, $config_file, $env)
    {
        $this->etcd_client = new Client($etcd_server);
        $this->etcd_client->setRoot(CommonConst::REGISTER_CENTER_ROOT);
        if (!is_dir(dirname($config_file)) || !is_writable(dirname($config_file))) {
            throw new FileNotWritableException($config_file);
        }
        $this->config_file = $config_file;
        $this->env = strtolower($env);
    }

    public function getServiceNodes()
    {
        $this->timer_id = \Swoole\Timer::tick(CommonConst::TTL_HEARTBEAT, [$this, 'get']);
        $this->stime = time();
    }

    public function get()
    {
        $res = $this->etcd_client->getKeysValue();
        $nodes = [];
        foreach ($res as $k => $v) {
            $value = json_decode($v, true);
            if ($this->env == strtolower($value['env']) && $value['status'] == CommonConst::SERVICE_NODE_STATUS_RUNNING) {
                $nodes[$value['service_name']][] = $value;
            }
        }
        //节点信息发生变更或者每10分钟更新一次
        if ($nodes != $this->node_list || time() - $this->stime > CommonConst::DISCOVERY_FILE_UPDATE_INTERVAL) {
            $this->stime = time();
            $this->node_list = $nodes;
            file_put_contents($this->config_file, json_encode($nodes));
        }
    }

    public function __destruct()
    {
        \Swoole\Timer::clear($this->timer_id);
    }
}
