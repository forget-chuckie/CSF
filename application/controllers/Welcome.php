<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/26
 * Time: 15:05
 */
class Welcome extends CoreController
{
    public function __construct(Array $params)
    {
        parent::__construct();

        $params["serv"]->send($params["fd"],$params["data"]);
    }
}