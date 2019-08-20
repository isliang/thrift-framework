<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-12
 * Time: 15:04
 */

namespace Isliang\Thrift\Framework\Constant;

class CommonConst
{
    /**
     * 注册中心中节点过期时间ttl 30s
     */
    const EXPIRE_TIME_SERVICE_NODE = 30;

    /**
     * 健康检查的ttl 20s
     */
    const TTL_HEARTBEAT = (self::EXPIRE_TIME_SERVICE_NODE * 1000 * 2)/3;

    /**
     * 服务发现 节点更新检查时间间隔
     */
    const TTL_DISCOVERY = 10;

    /**
     * 服务节点状态 运行
     */
    const SERVICE_NODE_STATUS_RUNNING = 'running';

    /**
     * 服务节点状态 下线
     */
    const SERVICE_NODE_STATUS_DOWN = 'down';

    /**
     * 服务注册中心 服务注册的根节点
     */
    const REGISTER_CENTER_ROOT = 'service';

    /**
     * 节点信息文件更新 最长时间间隔
     */
    const DISCOVERY_FILE_UPDATE_INTERVAL = 600;
}