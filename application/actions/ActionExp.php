<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/26
 * Time: 16:22
 */
class DefaultAction extends CoreAction
{
    public function distribute(Array $params)
    {
        $this->addTarget("receives/Welcome",$params);
        $this->pub();
    }
}