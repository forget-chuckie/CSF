<?php

class Welcome extends CoreController
{
    public function __construct(Array $params)
    {
        parent::__construct($params);
        $this->async("AsyncTask","process","hahaha");
        $params["serv"]->send($params["fd"], $params["data"]);
    }
}
