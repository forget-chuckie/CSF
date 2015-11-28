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
        $this->load->library("Database",null,"db");
        $res = $this->db->query("SELECT * FROM PriceFilter LIMIT 0,1");
        $this->db->close();
        var_dump($res);
        $this->serv->finish("done");
    }
}
