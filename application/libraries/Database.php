<?php

class Database
{
    protected $connection = null;
    protected $db = null;

    public function __construct()
    {
        $database = loadConfig("database", "database");
        $dsn = "mysql:host=" . $database["host"] . ";dbname=" . $database["dbname"];
        $user = $database["user"];
        $password = $database["password"];
        $this->connection = new Nette\Database\Connection($dsn, $user, $password,["lazy"=>true]);
        $this->db = new Nette\Database\Context($this->connection);
    }

    public function close()
    {
        $this->connection->disconnect();
    }

    public function __call($method, $args)
    {
        $callable = array($this->db, $method);
        return call_user_func_array($callable, $args);
    }
}
