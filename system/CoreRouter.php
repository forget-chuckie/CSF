<?php

require_once BASEPATH . "CoreAction.php";

class CoreRouter
{
    private static $_routeMap;

    public function __construct()
    {
        self::$_routeMap = loadConfig("receive_routes", "router");
    }

    public function route(Array $params)
    {
        if (!isset($params["router"])) {
            return;
        }

        $maps = self::$_routeMap;
        foreach ($maps as $key => $val) {
            if ($key == $params["router"]) {
                $val = str_replace('.php', '', trim($val, '/'));

                if (($lastSlash = strrpos($val, '/')) !== FALSE) {
                    $subdir = substr($val, 0, ++$lastSlash);
                    $router = substr($val, $lastSlash);
                } else {
                    $subdir = "";
                    $router = $val;
                }

                $actionInstance = &loadClass($router, "actions/" . $subdir, null, false);

                if ($actionInstance instanceof CoreAction) {
                    $actionInstance->distribute($params);
                }

                break;
            }
        }
    }
}