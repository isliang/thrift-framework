<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-13
 * Time: 11:05
 */
namespace Isliang\Thrift\Framework\Registration;

use Isliang\Thrift\Framework\ThriftHttpServer;

class Register
{
    /**
     * @var HeartbeatManager
     */
    private static $heart_beat_manager;
    /**
     * @var bool
     */
    private static $is_registered;

    /**
     * @var string
     */
    private static $register_key;

    /**
     * @var RegisterClient
     */
    private static $register_client;

    /**
     * @var \swoole_process
     */
    private static $process;

    public static function register($register_url, ServiceNode $service_node)
    {
        if (self::$is_registered) {
            return;
        }
        self::$register_key = $service_node->getRegisterKey();
        self::$register_client = new RegisterClient($register_url);
        self::$register_client->register($service_node);

        self::addProcess();

        self::$is_registered = true;
    }

    public static function addProcess()
    {
        self::$process = new \swoole_process(function ($process) {
            self::$heart_beat_manager = new HeartbeatManager(self::$register_client);
            self::$heart_beat_manager->registerServiceNode(self::$register_key);
            self::$heart_beat_manager->setStatus(true);
            self::$heart_beat_manager->start();

            \swoole_event_add($process->pipe, function ($pipe) use ($process) {
                $data = $process->read();
                if ('shutdown' == $data) {
                    self::$register_client->unRegister(self::$register_key);
                    self::$heart_beat_manager->unRegisterServiceNode(self::$register_key);
                    self::$heart_beat_manager->setStatus(false);
                }
            });

        });
        ThriftHttpServer::getServer()->addProcess(self::$process);
    }

    public static function unRegister()
    {
        if (self::$process) {
            self::$process->write("shutdown");
        }
    }
}