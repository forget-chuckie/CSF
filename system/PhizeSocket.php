<?php

require_once(BASEPATH . "CoreHelper.php");

set_error_handler("_errorHandler");
set_exception_handler("_exceptionHandler");
register_shutdown_function("_shutdownHandler");

if ($composer = loadConfig("composer_autoload", "config")) {
    if ($composer === TRUE) {
        file_exists(APPPATH . "vendor/autoload.php")
            ? require_once(APPPATH . "vendor/autoload.php")
            : die("composer autoload is set to TRUE but " . APPPATH . "vendor/autoload.php was not found.");
    } elseif (file_exists($composer)) {
        require_once($composer);
    } else {
        die('Could not find the specified ".$config["composer_autoload"]." path: ' . $composer);
    }
}

$charset = strtoupper(loadConfig("charset", "config"));
ini_set("default_charset", $charset);

if (extension_loaded("mbstring")) {
    define("MB_ENABLED", TRUE);
    @ini_set("mbstring.internal_encoding", $charset);
    mb_substitute_character("none");
} else {
    define("MB_ENABLED", FALSE);
}

if (extension_loaded("iconv")) {
    define("ICONV_ENABLED", TRUE);
    @ini_set("iconv.internal_encoding", $charset);
} else {
    define("ICONV_ENABLED", FALSE);
}

if (isPHP("5.6")) {
    ini_set("php.internal_encoding", $charset);
}

require_once BASEPATH . "CoreController.php";

function &getInstance()
{
    return CoreController::getInstance();
}

require_once(BASEPATH . "CoreServer.php");

$server = new CoreServer();
$server->run();


