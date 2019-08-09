<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-08
 * Time: 16:56
 */

namespace Isliang\Thrift\Framework;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Thrift\Factory\TStringFuncFactory;
use Thrift\Transport\TTransport;
use Thrift\Transport\TTransportException;

class TGuzzleTransport extends TTransport
{
    /**
     * @var $client ClientInterface
     */
    private static $client;

    protected $request;

    protected $response;

    private $scheme;

    private $host;

    private $port;

    private $uri;

    private $timeout;

    public function __construct(string $host, int $port = 80, string $uri = '', string $scheme = 'http')
    {
        if ((TStringFuncFactory::create()->strlen($uri) > 0) && ($uri{0} != '/')) {
            $uri = '/'.$uri;
        }
        $this->scheme = $scheme;
        $this->host = $host;
        $this->port = $port;
        $this->uri = $uri;
        $this->request = [];
        $this->response = null;
        $this->timeout = null;

        if (!self::$client) {
            self::$client = new Client();
        }
    }

    public function isOpen()
    {
        return true;
    }

    public function open()
    {
    }

    public function close()
    {
        $this->request = [];
    }

    public function read($len)
    {
        throw new \Exception('not support yet');
    }

    public function write($buf)
    {
        $this->request[] = $buf;
    }

    public function flush()
    {
        throw new \Exception('not support yet');
    }

    /**
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function async()
    {
        $request = implode('', $this->request);
        $this->request = [];

        $headers = [
            'Accept: application/x-thrift',
            'Content-Type: application/x-thrift',
            'Content-Length: '.TStringFuncFactory::create()->strlen($request)
        ];
        $host = $this->host.($this->port != 80 ? ':'.$this->port : '');
        $fullUrl = $this->scheme."://".$host.$this->uri;
        $guzzle_request = new Request('POST', $fullUrl, [], $request);
        $options = [
            'headers' => $headers,
        ];
        return self::$client->sendAsync($guzzle_request, $options);
    }
}