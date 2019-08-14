<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-13
 * Time: 14:27
 */
namespace Isliang\Thrift\Framework\Registration;

use Isliang\Thrift\Framework\Constant\CommonConst;

class HeartBeatManager
{
    private $timer_id;

    private $registered_list = [];

    /**
     * @var RegisterClient
     */
    private $client;

    /**
     * @var bool
     */
    private $status;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function start()
    {
        $this->timer_id = \Swoole\Timer::tick(CommonConst::TTL_HEARTBEAT, [$this, 'run']);
    }

    public function run()
    {
        foreach ($this->registered_list as $key) {
            if ($this->getStatus()) {
                $this->client->checkPass($key);
            } else {
                $this->client->checkFail($key);
            }
        }
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function registerServiceNode($key)
    {
        $this->registered_list[$key] = $key;
    }

    public function unRegisterServiceNode($key)
    {
        unset($this->registered_list[$key]);
    }
}