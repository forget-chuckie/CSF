<?php

class PoolModel extends CoreModel
{
    protected static $_pool = null;

    public function __construct()
    {
        parent::__construct();
        if (self::$_pool == null) {
            $this->loadDb();
        }
    }

    public function loadDb(){
    	$CN = &getInstance();
    	$CN->db = null;
    	$this->load->library("Db_pool", null, "db");
        self::$_pool = $this->db;
    }
}
