<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/12/2
 * Time: 13:53
 */
class DefaultConnect extends CoreController
{
    public function __construct(Array $param)
    {
        parent::__construct($param);
        var_dump("connect...");
    }
}