<?php

require_once BASEPATH . "CoreAnalysis.php";

class CoreServer
{
    private static $_router;
    private $config;

    public function __construct()
    {
        self::$_router = &loadClass("CoreRouter");
    }

    public function run()
    {
        $this->config = loadConfig("swoole", "config");
        $serv = new swoole_server($this->config["host"], $this->config["port"]);
        $serv->set($this->config);
        $serv->on("start", [$this, "onStart"]);
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

    public function onConnect($serv, $fd)
    {
        echo "Client:Connect.\n";
    }

    public function onReceive($serv, $fd, $fromId, $data)
    {
        $pData = $this->process($data);
        self::$_router->route([
            "serv" => $serv,
            "fd" => $fd,
            "fromId" => $fromId,
            "data" => $pData["data"],
            "router" => $pData["router"],
        ]);
    }

    public function onClose($serv, $fd)
    {
        echo "Client: Close.\n";
    }

    public function onTask($serv, $taskId, $fromId, $data)
    {
        if (isset($data["task"])) {
            $taskName = $data["task"];
            $handler = $data["handler"];
            $data = $data["data"];
            $taskInstance = &loadClass($taskName, "controllers", [
                "serv" => $serv,
                "taskId" => $taskId,
                "fromId" => $fromId,
                "data" => $data,
            ], false);
            $taskInstance->$handler($data);
        }
    }

    public function onFinish($serv, $taskId, $data)
    {
        echo "AsyncTask[$taskId] Finish: $data" . PHP_EOL;
    }

    protected function process($data)
    {
        $analysises = $this->config["data_analysises"];

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
}
