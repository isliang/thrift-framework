### 说明

这是一个简单的php thrift框架，基于apache官方提供的lib，并在不断完善中...

### 规则

- URI规则：{domain}/{service_name}/{module_name}
    - domain:微服务的域名，使用服务发现时，此为ip+port
    - service name:微服务的名称，例如订单服务，商品服务，以service开头，多个单词以中划线分隔
    - module name:每个微服务下可以有多个模块，例如订单创建相关，订单查询相关
    
- 目录规则
    - interface
        - composer.json
        - xxx.thrift    
    - implement
        - classes
        - composer.json
        - index.php
    - Framework
        - src
            - ThriftServiceLauncher.php:负责微服务服务端的启动
            - ThriftFactory.php:负责发起微服务请求
        - composer.json