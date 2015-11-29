<?php

class CoreAction
{
    private $_targets = [];

    public function addTarget($className, Array $params)
    {
        array_push($this->_targets, [
            "className" => $className,
            "params" => $params,
        ]);
    }

    public function pub()
    {
        if (count($this->_targets) > 0) {
            $maps = $this->_targets;
            foreach ($maps as $key => $val) {
                loadClass($val["className"], "controllers", $val["params"], false);
            }
        }
    }
}
