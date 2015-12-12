<?php

class Redis
{
    protected $redis = null;

    public function __construct()
    {
        $config = loadConfig("redis", "redis");
        $this->redis = new Predis\Client($config);
    }

    public function close()
    {
        $this->redis->disconnect();
    }

    public function __call($method, $args)
    {
        $callable = array($this->redis, $method);
        return call_user_func_array($callable, $args);
    }
}