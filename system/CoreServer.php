<?php

require_once BASEPATH . "CoreAnalysis.php";

class CoreServer
{
    private static $_router;
    private $analysisRoutes;
    private $sendRoutes;
    private $config;

    public function __construct()
    {
        self::$_router = &loadClass("CoreRouter");
    }

    public function run()
    {
        $this->config = loadConfig("swoole", "config");
        $this->sendRoutes = loadConfig("send_routes", "router");
        $this->analysisRoutes = loadConfig("analysis_routes", "router");

        $serv = new swoole_server($this->config["host"], $this->config["port"]);
        $serv->set($this->config);
        $serv->on("start", [$this, "onStart"]);
        $serv->on("workerStart", [$this, "onWorkerStart"]);
        $serv->on("connect", [$this, "onConnect"]);
        $serv->on('receive', [$this, 'onReceive']);
        $serv->on("close", [$this, "onClose"]);
        $serv->on("task", [$this, "onTask"]);
        $serv->on("finish", [$this, "onFinish"]);
        $serv->start();
    }

    public function onStart(swoole_server $serv)
    {
        echo "server start, listening at " . $this->config["port"] . "...\n";
    }

    public function onWorkerStart(swoole_server $serv, $worker_id)
    {
        var_dump($worker_id);
        if ($worker_id != 0) {
            return;
        }

        if(!empty($this->sendRoutes["normal"])){
            $this->_initNormalSender($this->sendRoutes["normal"], $serv);
        }

        if(!empty($this->sendRoutes["loop"])){
            $serv->tick($this->config["tick_time"], [$this, "onTimer"], $serv);
        }
    }

    public function onTimer($timer_id, $serv)
    {
        $this->_initTimerSender($this->sendRoutes["loop"], $serv);
    }

    public function onConnect(swoole_server $serv, $fd)
    {
        echo "Client:Connect.\n";
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
        echo "Client: Close.\n";
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
        $analysises = $this->analysisRoutes;

        foreach ($analysises as $key => $val) {
            $instance = &loadClass($val, "analysises", null, false);
            if ($instance instanceof CoreAnalysis) {
                $stop = false;
                $tmp = $instance->process($data, $stop);
                if ($stop) {
                    return $tmp;
                }
            }
        }
    }

    protected function _initNormalSender(Array $arr, swoole_server $serv)
    {
        $workerNum = count($arr);
        $stop = false;

        for ($i = 0; $i < $workerNum && !$stop; $i++) {
            $class = $arr[$i];
            $process = new swoole_process(function () use ($class, $serv, $stop) {
                $instance = &loadClass($class, "controllers/sends", ["serv" => $serv], false);
                $instance->process($stop);
            });

            $process->start();
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
