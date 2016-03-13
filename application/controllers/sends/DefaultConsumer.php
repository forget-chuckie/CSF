<?php

class DefaultPush extends CoreController
{
    private $serv = null;

    public function __construct(Array $params)
    {
        parent::__construct($params);
        $this->serv = $params["serv"];
    }

    public function process(&$stop)
    {
        //在这里可以执行类似nsq的listen操作
    }
}