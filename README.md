### 说明

这是一个简单的php thrift框架，基于apache官方提供的lib，并在不断完善中...

支持nginx+php-fpm下启动，以及nginx+swoole下启动

### 规则

- URI规则：{domain}/{service-name}/{moduleName}
    - domain:微服务的域名，使用服务发现时，此为ip+port
    - service-name:微服务的名称，例如订单服务，商品服务，以service开头，多个单词以中划线分隔
    - moduleName:每个微服务下可以有多个模块，例如订单创建相关，订单查询相关，首字母小写，驼峰结构
    
- 目录规则
    - interface
        - composer.json
        - xxx.thrift    
    - implement
        - classes
        - composer.json
        - index.php
    - Framework
        - bin
            - config.php:for服务注册的配置，包括注册中心地址及服务节点信息，`此为格式示例，实际项目中不要使用该配置文件`，实际项目中的注册
            配置建议放在`/data1/www/htdocs/config/thrift-service.php`中
            - launcher.php:for服务注册的服务启动入口
        - src
            - ThriftServiceLauncher.php:for微服务服务端，负责fpm模式下的微服务服务端的启动
            - ThriftFactory.php:for微服务客户端，返回对应的service，负责发起微服务请求
        - composer.json
        
### 服务端启动

#### fpm模式

在项目的index.php文件引入ThriftServiceLauncher,如下

```php
$launcher = new \Isliang\Thrift\Framework\ThriftServiceLauncher();
$launcher->handle(null, null);
```        

#### 服务发现模式

以cli方式启动， `php /path/to/project/vendor/bin/launcher.php`
需要在launcher.php中指定服务注册的配置文件，如下例中的 $config_file

```php
//launcher.php

$loader = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($loader)) {
    $loader = __DIR__ . '/../../../autoload.php';
}

require_once $loader;

$config_file = '/data1/www/htdocs/config/thrift-service.php';

if (!file_exists($config_file)) {
    $config_file = __DIR__ . '/config.php';
}

$config = require_once $config_file;

use \Isliang\Thrift\Framework\Config\RegisterConfig;
use \Isliang\Thrift\Framework\ThriftHttpServer;

$reg_config = new RegisterConfig($config);

$server = new ThriftHttpServer($reg_config);

$server->start();
```

config_file中的格式如下,需要根据实际情况更改注册中心的地址，服务节点的端口，scheme，env，service-name等信息

```php
return [
    'register_url' => 'http://192.168.199.234:2379',//服务注册中心地址
    'service' => [
        'host' => gethostbyname(gethostname()),//节点ip
        'port' => 80,//端口
        'scheme' => 'http',
        'env' => 'dev',//环境 dev-开发环境
        'service_name' => 'isliang-service-order',//服务 service name
        'weight' => (function() {
            $weight = 1;
            if (file_exists('/proc/cpuinfo')) {
                preg_match('/cpu cores\t: (\d+)/', file_get_contents('/proc/cpuinfo'), $match);
                if (!empty($match[1]) && is_int($match[1])) {
                    $weight = $match;
                }
            }
            return $weight;
        })(),//权重，初始化为cpu核心数
    ],
];
```

### 客户端调用

客户端调用提供两个方法，getService和getAsyncService,分别表示同步调用和异步调用，方法返回一个Proxy，使用Proxy调用RPC方法即可

客户端需要在入口文件中指定 日志目录以及节点信息存储文件
```php
global $global_config;
$global_config['endpoint_config_file'] = '';//节点信息存储的文件
$global_config['log_path'] = '';//客户端日志目录
```

#### 服务发现模式

如果是服务发现模式，节点信息存储的文件是由服务发现脚本生成的json文件，服务发现脚本是bin目录下的discovery.php，需要先在客户端机器上运行
服务发现脚本，生成节点信息存储文件，然后客户端读取该文件，发起服务请求。

#### 普通模式

如果是LB模式下，节点信息存储的文件为php文件，存放节点信息，格式如下
```php
return [
    'isliang-service-order' => [
        [
            'scheme'=> '',
            'host' => '', 
            'port' => '', 
            'weight' => ''
        ],
    ]
];
```

#### 同步模式

```php
$client = Isliang\Thrift\Framework\ThriftFactory::getService(
    'Isliang\Service\Order\ListServiceIf'
);
print_r($client->getOrderListByUids("1,2,3")) ;
```


#### 异步模式

引入[guzzle](https://github.com/guzzle/guzzle),请求支持异步并发模式,需要使用wait()方法获取最终返回结果


```php
$client = Isliang\Thrift\Framework\ThriftFactory::getAsyncService(
    'Isliang\Service\Order\ListServiceIf'
);
print_r($client->getOrderListByUids("1,2,3")->wait()) ;

print_r($client->getOrderListByUid(1)->wait()) ;
```

### 服务注册

基于swoole实现，由于 `Http\Server对Http协议的支持并不完整，建议仅作为应用服务器。并且在前端增加Nginx作为代理`，我们仍需要nginx的配合。在
swoole\http\server启动时，新建一个swoole_process，用于将节点信息注册到注册中心，并维持健康检查。

节点注册信息
    - key:service_name
    - value
        - scheme
        - host
        - port
        - weight:服务器权重
        - env:节点环境，开发(dev) (预发布)beta (线上)ga等
        - service_name

### 负载均衡

如何实现客户端的负载均衡...
