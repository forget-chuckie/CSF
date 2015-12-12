<?php

require_once BASEPATH . "CoreAnalysis.php";

class CoreServer
{
    private static $_router;
    private $_analysisRoutes;
    private $_sendRoutes;
    private $_closeRoutes;
    private $_connectRoutes;
    private $_config;
    private $_timer;

    public function __construct()
    {
        self::$_router = &loadClass("CoreRouter");
    }

    public function run()
    {
        $this->_config = loadConfig("swoole", "config");
        $this->_sendRoutes = loadConfig("send_routes", "router");
        $this->_analysisRoutes = loadConfig("analysis_routes", "router");
        $this->_closeRoutes = loadConfig("close_routes", "router");
        $this->_connectRoutes = loadConfig("connect_routes", "router");

        $serv = new swoole_server($this->_config["host"], $this->_config["port"]);
        $serv->set($this->_config);
        $serv->on("start", [$this, "onStart"]);
        $serv->on("connect", [$this, "onConnect"]);
        $serv->on('receive', [$this, 'onReceive']);
        $serv->on("close", [$this, "onClose"]);
        $serv->on("task", [$this, "onTask"]);
        $serv->on("finish", [$this, "onFinish"]);

        if (!empty($this->_sendRoutes["normal"])) {
            $this->_initNormalSender($this->_sendRoutes["normal"], $serv);
        }

        $serv->start();
    }

    public function onStart(swoole_server $serv)
    {
        if (!empty($this->_sendRoutes["loop"])) {
            $this->_timer = $serv->tick($this->_config["tick_time"], [$this, "onTimer"], $serv);
        }

        echo "server start, listening at " . $this->_config["port"] . "...\n";
    }

    public function onTimer($timerId, $serv)
    {
        $this->_initTimerSender($this->_sendRoutes["loop"], $serv);
    }

    public function onConnect(swoole_server $serv, $fd)
    {
        $conns = $this->_connectRoutes;
        foreach ($conns as $key => $val) {
            loadClass($val, "controllers/connects", [
                "serv" => $serv,
                "fd" => $fd,
            ], false);
        }
    }

    public function onReceive(swoole_server $serv, $fd, $fromId, $data)
    {
        $pData = $this->_process($data);
        self::$_router->route([
            "serv" => $serv,
            "fd" => $fd,
            "fromId" => $fromId,
            "data" => $pData["data"],
            "router" => $pData["router"],
        ]);
    }

    public function onClose(swoole_server $serv, $fd)
    {
        $closes = $this->_closeRoutes;
        foreach ($closes as $key => $val) {
            loadClass($val, "controllers/closes", [
                "serv" => $serv,
                "fd" => $fd,
            ], false);
        }
    }

    public function onTask(swoole_server $serv, $taskId, $fromId, $data)
    {
        if (isset($data["task"])) {
            $taskName = $data["task"];
            $handler = $data["handler"];
            $data = $data["data"];
            $taskInstance = &loadClass($taskName, "controllers/tasks", [
                "serv" => $serv,
                "taskId" => $taskId,
                "fromId" => $fromId,
                "data" => $data,
            ], false);
            $taskInstance->$handler($data);
        }
    }

    public function onFinish(swoole_server $serv, $taskId, $data)
    {
        echo "AsyncTask[$taskId] Finish: $data" . PHP_EOL;
    }

    protected function _process($data)
    {
        $analysises = $this->_analysisRoutes;
        $tmp = $data;

        foreach ($analysises as $key => $val) {
            $instance = &loadClass($val, "analysises", null, false);
            if ($instance instanceof CoreAnalysis) {
                $stop = false;
                $tmp = $instance->process($tmp, $stop);
                if ($stop) {
                    return $tmp;
                }
            }
        }

        return $tmp;
    }

    protected function _initNormalSender(Array $arr, swoole_server $serv)
    {
        $workerNum = count($arr);
        $stop = false;

        for ($i = 0; $i < $workerNum; $i++) {
            $class = $arr[$i];

            $process = new swoole_process(function () use ($class, $serv, $stop) {
                $instance = &loadClass($class, "controllers/sends", ["serv" => $serv], false);
                $instance->process($stop);
            });

            $serv->addProcess($process);

            if($stop){
                break;
            }
        }
    }

    protected function _initTimerSender(Array $arr, swoole_server $serv)
    {
        foreach ($arr as $key => $val) {
            $instance = &loadClass($val, "controllers/sends", ["serv" => $serv], false);

            $stop = false;
            $instance->process($stop);

            if ($stop) {
                break;
            }
        }
    }
}
