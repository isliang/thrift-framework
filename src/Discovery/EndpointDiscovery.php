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
use Swoole\Mysql\Exception;

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

    private $register_servers;

    private $root = CommonConst::REGISTER_CENTER_ROOT;

    public function __construct($etcd_server, $config_file, $env)
    {
        $this->etcd_client = new Client($etcd_server);
        $this->etcd_client->setRoot($this->root);
        if (!is_dir(dirname($config_file)) || !is_writable(dirname($config_file))) {
            throw new FileNotWritableException($config_file);
        }
        $this->config_file = $config_file;
        $this->env = strtolower($env);
        $this->register_servers = [$etcd_server];
    }

    public function getServiceNodes()
    {
        $this->timer_id = \Swoole\Timer::tick(CommonConst::TTL_DISCOVERY, [$this, 'get']);
        $this->stime = time();
    }

    public function get()
    {
        $this->connect();
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

    private function connect()
    {
        $register_servers = [];
        foreach ($this->register_servers as $server) {
            try {
                $this->etcd_client = new Client($server);
                $register_servers = $this->getMembers();
            } catch (Exception $e) {
                $this->etcd_client = null;
            }
            $this->etcd_client->setRoot($this->root);
        }
        if (empty($this->etcd_client)) {
            throw new RegisterServerException();
        }
        $this->register_servers = $register_servers;
    }

    private function getMembers()
    {
        $list = [];
        $members = $this->etcd_client->getVersion('/v2/members');
        if ($members['members']) {
            foreach ($members['members'] as $member) {
                if (!empty($member['clientURLs'])) {
                    foreach ($member['clientURLs'] as $url) {
                        $list[] = $url;
                    }
                }
            }
        }
        return array_unique($list);
    }

    public function __destruct()
    {
        if ($this->timer_id) {
            \Swoole\Timer::clear($this->timer_id);
        }
    }
}
