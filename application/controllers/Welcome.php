<?php

class Welcome extends CoreController
{
    public function __construct(Array $params)
    {
        parent::__construct($params);
        $params["serv"]->send($params["fd"], $params["data"]);
    }
}
