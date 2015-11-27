<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/27
 * Time: 13:46
 */
class NSQ
{
    protected $nsq = null;

    public function __construct()
    {
        $this->nsq = new nsqphp\nsqphp();
    }

    public function __call($method, $args)
    {
        $callable = array($this->nsq, $method);
        return call_user_func_array($callable, $args);
    }
}