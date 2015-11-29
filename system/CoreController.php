<?php

class CoreController
{
    private static $_instance = null;
    private $_params = null;

    public function __construct(Array $params)
    {
        self::$_instance = &$this;
        $this->_params = $params;
        $this->load = &loadClass("CoreLoader");
        logMessage('info', 'Controller Class Initialized');
    }

    public function async($task, $handler, Array $data)
    {
        $serv = $this->_params["serv"];
        $serv->task([
            "task" => $task,
            "data" => $data,
            "handler" => $handler,
        ]);
    }

    public static function getInstance()
    {
        return self::$_instance;
    }
}
