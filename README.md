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

### 客户端调用

客户端调用提供两个方法，getService和getAsyncService,分别表示同步调用和异步调用，方法返回一个Proxy，使用Proxy调用RPC方法即可

#### 同步模式

```php
$client = Isliang\Thrift\Framework\ThriftFactory::getService(
    ['host' => 'thrift.service.com','port' => 80],
    'Isliang\Service\Order\ListServiceIf'
);
print_r($client->getOrderListByUids("1,2,3")) ;
```


#### 异步模式

引入[guzzle](https://github.com/guzzle/guzzle),请求支持异步并发模式,需要使用wait()方法获取最终返回结果


```php
$client = Isliang\Thrift\Framework\ThriftFactory::getAsyncService(
    ['host' => 'thrift.service.com','port' => 80],
    'Isliang\Service\Order\ListServiceIf'
);
print_r($client->getOrderListByUids("1,2,3")->wait()) ;

print_r($client->getOrderListByUid(1)->wait()) ;
```

### 服务注册

基于swoole实现，由于 `Http\Server对Http协议的支持并不完整，建议仅作为应用服务器。并且在前端增加Nginx作为代理`，我们仍需要nginx的配合。在
swoole\http\server启动时，新建一个swoole_process，用于将节点信息注册到注册中心，并维持健康检查。

