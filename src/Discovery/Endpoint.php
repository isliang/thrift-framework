<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-16
 * Time: 16:16
 */

namespace Isliang\Thrift\Framework\Discovery;

class Endpoint
{
    /**
     * @var string http or https
     */
    private $scheme;

    /**
     * @var string host
     */
    private $host;

    /**
     * @var int port
     */
    private $port;

    /**
     * @var int 权重
     */
    private $weight;

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

    public function __toString()
    {
        return "{$this->scheme}://{$this->host}:{$this->port}";
    }
}