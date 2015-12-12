<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/12/2
 * Time: 13:53
 */
class DefaultClose extends CoreController
{
    private $_connHashName = "ycn_user_connects";

    public function __construct(Array $param)
    {
        parent::__construct($param);
        var_dump("close...");
    }
}