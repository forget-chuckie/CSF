<?php

if (!function_exists('isPHP')) {
    function isPHP($version)
    {
        static $_isPHP;
        $version = (string)$version;

        if (!isset($_isPHP[$version])) {
            $_isPHP[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_isPHP[$version];
    }
}

if (!function_exists('isWriteable')) {
    function isWriteable($file)
    {
        if (DIRECTORY_SEPARATOR === '/' && (isPHP('5.4') OR !ini_get('safe_mode'))) {
            return is_writable($file);
        }

        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE) {
                return FALSE;
            }

            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        } elseif (!is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE) {
            return FALSE;
        }

        fclose($fp);
        return TRUE;
    }
}

if (!function_exists("loadClass")) {
    function &loadClass($class, $directory = '', $param = NULL, $isCache = true)
    {
        static $_classes = array();

        if (isset($_classes[$class])) {
            return $_classes[$class];
        }

        $name = FALSE;

        foreach (array(APPPATH, BASEPATH) as $path) {
            if (file_exists($path . $directory . '/' . $class . '.php')) {
                $name = $class;

                if (class_exists($name, FALSE) === FALSE) {
                    require_once($path . $directory . '/' . $class . '.php');
                }

                break;
            }
        }

        if ($name === FALSE) {
            die('Unable to locate the specified class: ' . $class . '.php');
        }

        $instance = isset($param)
            ? new $name($param)
            : new $name();

        if ($isCache) {
            $_classes[$class] = $instance;
        }

        return $instance;
    }
}

if (!function_exists("loadConfig")) {
    function loadConfig($name, $section = null)
    {
        static $_config;

        $config = [];
        if (!isset($_config[$name])) {
            $path = APPPATH . "config/" . $section . ".php";
            if (file_exists($path)) {
                require_once($path);
                foreach ($config as $key => $val) {
                    $_config[$key] = $val;
                }
            }
        }

        return isset($_config[$name]) ? $_config[$name] : die("load config error,can't find " . $section . "'s " . $name);
    }
}


if (!function_exists('logMessage')) {
    function logMessage($level, $message)
    {
        static $_log;

        if ($_log === NULL) {
            $_log[0] =  &loadClass('CoreLog');
        }

        $_log[0]->writeLog($level, $message);
    }
}

if (!function_exists("_errorHandler")) {
    function _errorHandler($severity, $message, $filepath, $line)
    {
        $isError = (((E_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $severity) === $severity);

        if (($severity & error_reporting()) !== $severity) {
            return;
        }

        $_error = &loadClass('CoreException');
        $_error->logException($severity, $message, $filepath, $line);

        if ($isError) {
            die("error occurred,the main process exit.");
        }
    }
}

if (!function_exists("_exceptionHandler")) {
    function _exceptionHandler($exception)
    {
        $_error =  &loadClass('CoreException');
        $_error->logException('error', 'Exception: ' . $exception->getMessage(), $exception->getFile(), $exception->getLine());

        die("exception occurred,the main process exit.");
    }
}

if (!function_exists("_shutdownHandler")) {
    function _shutdownHandler()
    {
        $last_error = error_get_last();
        if (isset($last_error) &&
            ($last_error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING))
        ) {
            _errorHandler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }
}