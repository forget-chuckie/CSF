<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/27
 * Time: 13:59
 */
class SSDB
{
    protected $ssdb = null;

    public function __construct()
    {
        $ssdb = [];
        require_once APPPATH . "config/ssdb.php";

        $this->ssdb = new SSDB\Client($ssdb["host"], $ssdb["port"]);
    }

    public function __call($method, $args)
    {
        $callable = array($this->ssdb, $method);
        return call_user_func_array($callable, $args);
    }
}