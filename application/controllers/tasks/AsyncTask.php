<?php

class AsyncTask extends CoreController
{
    private $serv = null;

    public function __construct(Array $params)
    {
        parent::__construct($params);
	    $this->serv = $params["serv"];
    }


    public function process($data)
    {
        $this->load->library("NSQ",null,"nsq");
        $this->nsq->publishTo('localhost')->publish('mytopic', new nsqphp\Message\Message('some message payload'));
        $this->nsq->close();
        $this->serv->finish("done");
    }
}
