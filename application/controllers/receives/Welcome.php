<?php

class Welcome extends CoreController
{
	private $serv = null;
	private $fd = null;

    public function __construct(Array $params)
    {
        parent::__construct($params);
        $this->serv = $params["serv"];
        $this->fd = $params["fd"];
        $this->process($params["data"]);
    }

    public function process($data)
    {
        //同步阻塞，等待task进程返回数据
        $result = $this->syncTask("SyncTask","process","something");

        //投递任务给task后继续执行
        $this->async("AsyncTask","process","something...");

        $this->serv->send($this->fd,"success");
    }
}
