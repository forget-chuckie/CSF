<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/25
 * Time: 14:07
 */
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

    public function onReceive($serv, $fd, $from_id, $data)
    {
        $pData = $this->process($data);
        self::$_router->route([
            "serv" => $serv,
            "fd" => $fd,
            "from_id" => $from_id,
            "data" => $pData["data"],
            "router" => $pData["router"],
        ]);
    }

    public function onClose($serv, $fd)
    {
        echo "Client: Close.\n";
    }

    /**
     * 数据格式为：标志*数据\r\n
     * @param $data
     * @return array
     */
    protected function process($data)
    {
        return [
            "data" => $data,
            "router" => 10001,
        ];
    }
}