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
        var_dump("default push start...");
        $stop = true;
    }
}