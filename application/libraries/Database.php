<?php

class Database
{
    protected $db = null;

    public function __construct()
    {
        $database = [];
        require_once APPPATH . "config/database.php";

        $dsn = "mysql:host=" . $database["host"] . ";dbname=" . $database["dbname"];
        $user = $database["user"];
        $password = $database["password"];
        $connection = new Nette\Database\Connection($dsn, $user, $password);
        $this->db = new Nette\Database\Context($connection);
    }

    public function __call($method, $args)
    {
        $callable = array($this->db, $method);
        return call_user_func_array($callable, $args);
    }
}