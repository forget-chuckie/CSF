<?php

class Mongo_client
{
    protected $db = null;

    public function __construct()
    {
        $mongodb = [];
        require_once APPPATH . "config/mongodb.php";

        $options = [
            "db" => $mongodb["db"],
            "connect" => $mongodb["connect"],
        ];

        $username = $mongodb["username"];
        if ($username != "") {
            $options["username"] = $username;
        }

        $password = $mongodb["password"];
        if ($password != "") {
            $options["password"] = $password;
        }

        $mongo = new MongoClient("mongodb://" . $mongodb["host"] . ":" . $mongodb["port"], $options);
        $this->db = $mongo->selectDB($mongodb["db"]);
    }

    public function __call($method, $args)
    {
        $callable = array($this->db, $method);
        return call_user_func_array($callable, $args);
    }
}