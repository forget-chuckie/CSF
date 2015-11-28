<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/28
 * Time: 16:06
 */
class RedisPush extends CoreController
{
    private $serv = null;

    public function __construct(Array $params)
    {
        parent::__construct($params);
        $this->serv = $params["serv"];
    }

    public function process(&$stop)
    {
        var_dump("redis start...");
        $stop = true;
    }
}