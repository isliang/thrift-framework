<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-13
 * Time: 17:21
 */
namespace Isliang\Thrift\Framework\Config;

/**
 * Class RegisterConfig
 * @package Isliang\Thrift\Framework\Config
 * 注册配置类 注册中心地址，节点信息等
 */
class RegisterConfig
{
    /**
     * @var string 注册中心地址
     */
    private $register_url;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var int
     */
    private $weight;

    /**
     * @var string 服务名称
     */
    private $service_name;

    /**
     * @var string http or https
     */
    private $scheme;

    /**
     * @var string
     */
    private $env;

    /**
     * @return string
     */
    public function getRegisterUrl(): string
    {
        return $this->register_url;
    }

    /**
     * @param string $register_url
     * @return $this
     */
    public function setRegisterUrl(string $register_url)
    {
        $this->register_url = $register_url;
        return $this;
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
     * @return $this
     */
    public function setHost(string $host)
    {
        $this->host = $host;
        return $this;
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
     * @return $this
     */
    public function setPort(int $port)
    {
        $this->port = $port;
        return $this;
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
     * @return $this
     */
    public function setWeight(int $weight)
    {
        $this->weight = $weight;
        return $this;
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
     * @return $this
     */
    public function setServiceName(string $service_name)
    {
        $this->service_name = $service_name;
        return $this;
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
     * @return $this
     */
    public function setScheme(string $scheme)
    {
        $this->scheme = $scheme;
        return $this;
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
     * @return $this
     */
    public function setEnv(string $env)
    {
        $this->env = $env;
        return $this;
    }
}