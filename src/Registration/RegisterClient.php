<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-13
 * Time: 11:08
 */

namespace Isliang\Thrift\Framework\Registration;

use Isliang\Thrift\Framework\Constant\CommonConst;
use \LinkORB\Component\Etcd\Client;
use LinkORB\Component\Etcd\Exception\KeyNotFoundException;
use Swoole\Mysql\Exception;

class RegisterClient
{
    /**
     * @var Client
     */
    private $etcd_client;

    private $register_servers;

    private $root = CommonConst::REGISTER_CENTER_ROOT;

    public function __construct($etcd_server)
    {
        $this->etcd_client = new Client($etcd_server);
        $this->etcd_client->setRoot($this->root);
        $this->register_servers = [$etcd_server];
    }

    /**
     * @param ServiceNode $service_node
     * @throws KeyNotFoundException
     */
    public function register(ServiceNode $service_node)
    {
        $this->connect();
        $service_node->setStatus(CommonConst::SERVICE_NODE_STATUS_RUNNING);
        $value = $this->getValue($service_node->getRegisterKey());
        if ($value) {
            $value = json_decode($value, true);
            $old_status = $value['status'] ?: CommonConst::SERVICE_NODE_STATUS_DOWN;
            $service_node->setStatus($old_status);
            $this->etcd_client->update(
                $service_node->getRegisterKey(),
                $service_node->__toString(),
                CommonConst::EXPIRE_TIME_SERVICE_NODE,
                ['value' => json_encode($value)]
            );
        } else {
            $this->etcd_client->set(
                $service_node->getRegisterKey(),
                $service_node->__toString(),
                CommonConst::EXPIRE_TIME_SERVICE_NODE
            );
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

    /**
     * @param $key
     * @return string|null
     */
    private function getValue($key)
    {
        try {
            return $this->etcd_client->get($key);
        } catch (KeyNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param $key
     * @throws KeyNotFoundException
     * 节点下线 状态置为down
     */
    public function unRegister($key)
    {
        $this->connect();
        $old_value = $this->getValue($key);
        if ($old_value) {
            $value = json_decode($old_value, true);
            $value['status'] = CommonConst::SERVICE_NODE_STATUS_DOWN;
            $this->etcd_client->update(
                $key,
                json_encode($value),
                null,
                ['value' => $old_value]
            );
        }
    }

    /**节点有效，刷新ttl
     * @param $key
     * @throws KeyNotFoundException
     */
    public function checkPass($key)
    {
        $this->connect();
        $value = $this->getValue($key);
        if ($value) {
            $this->etcd_client->update(
                $key,
                $value,
                CommonConst::EXPIRE_TIME_SERVICE_NODE
            );
        }
    }

    /**
     * 节点无效，删除注册中心中的节点信息
     * @param $key
     * @throws KeyNotFoundException
     */
    public function checkFail($key)
    {
        $this->connect();
        $value = $this->getValue($key);
        if ($value) {
            $value = json_decode($value, true);
            $value['status'] = CommonConst::SERVICE_NODE_STATUS_DOWN;
            $this->etcd_client->update(
                $key,
                json_encode($value)
            );
        }
    }
}