<?php

namespace Convee\Core;


class Redis
{
    private $config = array();

    /**
     * @var \Redis
     */
    private $handler;
    /**
     * @var \Redis
     */
    private $handlerMaster;
    /**
     * @var \Redis
     */
    private $handlerSlave;


    public function __construct($config)
    {
        if (!class_exists('Redis')) {
            throw new \RedisException('redis not support!');
        }
        if (empty($config['master']) || empty($config['master']['host']) || empty($config['master']['port'])) {
            throw new \RedisException('redis config error.');
        }
        $this->config = $config;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \RedisException
     */
    public function __call($name, $arguments)
    {
        if (in_array(strtolower($name), [
            'append',
            'getset',
            'move',
            'rename',
            'renamekey',
            'renamenx',
            'settimeout',
            'pexpire',
            'pexpireat',
            'expire',
            'expireat',
            'setrange',
            'setbit',
            'setex',
            'psetex',
            'setnx',
            'del',
            'delete',
            'sort',
            'decr',
            'decrby',
            'decrbyfloat',
            'incr',
            'incrby',
            'incrbyfloat',
            'mset',
            'msetnx',
            'restore',
            'migrate',
            'hdel',
            'hincrby',
            'hincrbyfloat',
            'hmset',
            'hset',
            'hsetnx',
            'blpop',
            'brpop',
            'brpoplpush',
            'linsert',
            'lpop',
            'lpush',
            'lpushx',
            'lrem',
            'lremove',
            'lset',
            'ltrim',
            'listtrim',
            'rpop',
            'rpoplpush',
            'rpush',
            'rpushx',
            'sadd',
            'sdiffstore',
            'sinterstore',
            'smove',
            'srandmember',
            'srem',
            'sremove',
            'sunionstore',
            'zadd',
            'zincrby',
            'zinter',
            'zrem',
            'zdelete',
            'zremrangebyrank',
            'zdeleterangebyrank',
            'zremrangebyscore',
            'zdeleterangebyscore',
            'zunion',
            'zinterstore',
            'zunionstore',
            'psubscribe',
            'publish',
            'subscribe',
            'pubsub',
            'exec',
            'eval',
            'evalsha',
            'script'
        ])) {
            $this->connectMaster();
        } else {
            $this->connectSlave();
        }
        return call_user_func_array(array($this->handler, $name), $arguments);
    }

    public function __destruct()
    {
        if ($this->handlerMaster) {
            $this->handlerMaster->close();
        }

        if ($this->handlerSlave) {
            $this->handlerSlave->close();
        }
    }

    /**
     * 连接redis主服务器
     */
    private function connectMaster()
    {
        if (!$this->handlerMaster) {
            $config = $this->config['master'];
            $this->handler = new \Redis();
            $this->handler->connect($config['host'], $config['port']);
            if (!$this->handler) {
                throw new \RedisException(sprintf('redis connect fail at %s:%s', $config['host'], $config['port']));
            }
            $this->handlerMaster = $this->handler;
        } else {
            $this->handler = $this->handlerMaster;
        }


    }

    /**
     * 连接redis从服务器
     */
    private function connectSlave()
    {
        if (!$this->handlerSlave) {
            if (isset($this->config['slaves'])) {
                $config = $this->config['slaves'][rand(0, count($this->config['slaves']) - 1)];
            } else {
                $config = $this->config['master'];
            }
            $this->handler = new \Redis();
            $this->handler->connect($config['host'], $config['port']);
            if (!$this->handler) {
                throw new \RedisException(sprintf('redis connect fail at %s:%s', $config['host'], $config['port']));
            }
            $this->handlerSlave = $this->handler;
        } else {
            $this->handler = $this->handlerSlave;
        }
    }
}