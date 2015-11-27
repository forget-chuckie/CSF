<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/26
 * Time: 15:24
 */
class CoreModel
{
    public function __construct()
    {
        logMessage('info', 'Model Class Initialized');
    }

    public function __get($key)
    {
        return get_instance()->$key;
    }
}