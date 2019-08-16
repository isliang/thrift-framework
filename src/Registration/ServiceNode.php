<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-12
 * Time: 14:28
 */
namespace Isliang\Thrift\Framework\Registration;

use Isliang\Thrift\Framework\Constant\CommonConst;

class ServiceNode
{
    /**
     * @var string
     */
    private $scheme;
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var int 权重
     */
    private $weight;

    /**
     * @var string 服务名称
     */
    private $service_name;

    /**
     * @var string 环境, e.g. ga beta dev etc.
     */
    private $env;

    /**
     * @var string 服务节点状态 running down
     */
    private $status = CommonConst::SERVICE_NODE_STATUS_DOWN;

    public function __construct($service_name, $host, $port = 80, $env = 'dev', $weight = 1, $scheme = 'http')
    {
        $this->service_name = $service_name;
        $this->host = $host;
        $this->port = $port;
        $this->weight = $weight;
        $this->scheme = $scheme;
        $this->env = $env;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     */
    public function setScheme(string $scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port)
    {
        $this->port = $port;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     */
    public function setWeight(int $weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->service_name;
    }

    /**
     * @param string $service_name
     */
    public function setServiceName(string $service_name)
    {
        $this->service_name = $service_name;
    }

    /**
     * @return string
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    /**
     * @param string $env
     */
    public function setEnv(string $env)
    {
        $this->env = $env;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     * 节点在注册中心里的key
     */
    public function getRegisterKey()
    {
        return "/{$this->env}/{$this->service_name}/{$this->host}::{$this->port}";
    }


    public function __toString()
    {
        return json_encode([
            'scheme' => $this->getScheme(),
            'host' => $this->getHost(),
            'port' => $this->getPort(),
            'weight' => $this->getWeight(),
            'env' => $this->getEnv(),
            'service_name' => $this->getServiceName(),
            'status' => $this->getStatus(),
        ]);
    }
}