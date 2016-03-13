<?php

class SSDB
{
    protected $ssdb = null;

    public function __construct()
    {
        $ssdb = loadConfig("ssdb", "ssdb");
        $this->ssdb = new SSDB\Client($ssdb["host"], $ssdb["port"]);
    }

    public function __destruct()
    {
        logMessage('info','ssdb destruct...');
        $this->ssdb->close();
    }

    public function __call($method, $args)
    {
        $callable = array($this->ssdb, $method);
        return call_user_func_array($callable, $args);
    }
}