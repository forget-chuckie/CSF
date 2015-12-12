<?php

class CoreModel
{
    public function __construct()
    {
        logMessage('info', 'Model Class Initialized');
    }

    public function __get($key)
    {
        return getInstance()->$key;
    }
}