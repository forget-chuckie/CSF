<?php

require_once APPPATH . "libraries/Database.php";

class Db_pool extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        //不调用父类自身的析构函数用来关闭连接
    }
}