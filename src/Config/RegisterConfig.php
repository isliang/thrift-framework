<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-13
 * Time: 17:21
 */
namespace Isliang\Thrift\Framework\Config;

use Isliang\Thrift\Framework\Exception\RegisterConfigErrorException;

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
     * RegisterConfig constructor.
     * @param $config
     * @throws RegisterConfigErrorException
     */
    public function __construct($config)
    {
        $this->setRegisterUrl($config['register_url']);
        if ($service = $config['service']) {
            $this->setHost($service['host']);
            $this->setPort($service['port']);
            $this->setScheme($service['scheme']);
            $this->setServiceName($service['service_name']);
            $this->setEnv($service['env']);
            $this->setWeight($service['weight']);
        }
        $this->check();
    }

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
        if (!empty($register_url) && is_string($register_url)) {
            $this->register_url = $register_url;
        }
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
        if (!empty($host) && is_string($host)) {
            $this->host = $host;
        }
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
        if (!empty($port) && is_int($port)) {
            $this->port = $port;
        }
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
        if (!empty($weight) && is_int($weight)) {
            $this->weight = $weight;
        }
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
        if (!empty($service_name) && is_string($service_name)) {
            $this->service_name = $service_name;
        }
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
        if (!empty($scheme) && is_string($scheme)) {
            $scheme = strtolower($scheme);
            if ($scheme == 'http' || $scheme == 'https') {
                $this->scheme = $scheme;
            }
        }
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
        if (!empty($env) && is_string($env)) {
            $this->env = $env;
        }
        return $this;
    }

    /**
     * @throws RegisterConfigErrorException
     */
    public function check()
    {
        $vars = get_class_vars(self::class);
        foreach ($vars as $var => $val) {
            if (empty($this->$var)) {
                throw new RegisterConfigErrorException($var);
            }
        }
    }
}