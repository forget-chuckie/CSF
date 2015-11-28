<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/28
 * Time: 15:31
 */
class QueuePush extends CoreController
{
    private $serv = null;

    public function __construct(Array $params)
    {
        parent::__construct($params);
        $this->serv = $params["serv"];
    }

    public function process(&$stop)
    {
        $serv = $this->serv;
        $lookup = new nsqphp\Lookup\FixedHosts('localhost');
        $nsq = new nsqphp\nsqphp($lookup);

        $nsq->subscribe('mytopic', "mychannel", function ($msg) use ($serv) {
            foreach ($serv->connections as $fd) {
                $serv->send($fd, "READ\t" . $msg->getId() . "\t" . $msg->getPayload() . "\n");
            }
        });

        $nsq->run();
    }
}