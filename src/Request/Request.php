<?php
/**
 * Created by PhpStorm.
 * User: yesuhuangsi
 * Date: 2019-08-14
 * Time: 14:02
 */

namespace Isliang\Thrift\Framework\Request;

interface Request
{
    /**
     * @param string $key
     * @return string
     * GET参数
     */
    public function get($key);

    /**
     * @param string $key
     * @return string
     * POST参数
     */
    public function post($key);

    /**
     * @param string $key
     * @return string
     * SERVER参数
     */
    public function server($key);

    /**
     * @return string
     * 原始post数据 php://input
     */
    public function content();
}