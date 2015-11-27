<?php

/**
 * Created by IntelliJ IDEA.
 * User: yang
 * Date: 2015/11/25
 * Time: 14:07
 */

require_once BASEPATH . "CoreAction.php";

class CoreRouter
{
    private static $_routeMap;

    public function __construct()
    {
        self::$_routeMap = loadConfig("route", "router");
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

                $actionInstance = &loadClass($router, "actions/" . $subdir);

                if ($actionInstance instanceof CoreAction) {
                    $actionInstance->distribute($params);
                }

                break;
            }
        }
    }
}