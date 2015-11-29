<?php

class CoreException
{
    public $ob_level;

    public $levels = array(
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parsing Errolr',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Runtime Notice'
    );

    public function __construct()
    {
        $this->ob_level = ob_get_level();
    }

    public function logException($severity, $message, $filepath, $line)
    {
        $severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;
        logMessage('error', 'Severity: ' . $severity . ' --> ' . $message . ' ' . $filepath . ' ' . $line);
    }
}
