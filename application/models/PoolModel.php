<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2016/3/11
 * Time: 17:28
 */
class PoolModel extends CoreModel
{
    protected static $_pool = null;

    public function __construct()
    {
        parent::__construct();
        if (self::$_pool == null) {
            $this->load->library("Db_pool", null, "db");
            self::$_pool = $this->db;
        }
    }
}