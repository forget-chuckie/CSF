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
    private $_params = null;

    public function __construct(Array $params)
    {
        self::$_instance = &$this;
        $this->_params = $params;
        $this->load = &loadClass("CoreLoader", null, null, false);
        logMessage('info', 'Controller Class Initialized');
    }

    public function asyncTask($task, $handler, Array $data)
    {
        $serv = $this->_params["serv"];
        $serv->task([
            "task" => $task,
            "data" => $data,
            "handler" => $handler,
        ]);
    }

    public function syncTask($task, $handler, Array $data = [], $timeout = 0.5)
    {
        $serv = $this->_params["serv"];
        return $serv->taskwait([
            "task" => $task,
            "data" => $data,
            "handler" => $handler,
        ], $timeout);
    }

    public static function getInstance()
    {
        return self::$_instance;
    }
}
