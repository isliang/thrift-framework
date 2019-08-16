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

class RegisterClient
{
    /**
     * @var Client
     */
    private $etcd_client;

    public function __construct($etcd_server)
    {
        $this->etcd_client = new Client($etcd_server);
        $this->etcd_client->setRoot('service');
    }

    /**
     * @param ServiceNode $service_node
     * @throws KeyNotFoundException
     */
    public function register(ServiceNode $service_node)
    {
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