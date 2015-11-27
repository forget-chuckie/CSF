<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/25
 * Time: 14:06
 */
class CoreController
{
    private static $_instance = null;

    public function __construct()
    {
        self::$_instance = &$this;
        $this->load = &loadClass("CoreLoader");
        $this->load->initialize();

        logMessage('info', 'Controller Class Initialized');
    }

    public static function getInstance()
    {
        return self::$_instance;
    }
}