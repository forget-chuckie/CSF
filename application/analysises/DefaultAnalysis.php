<?php

class DefaultAnalysis extends CoreAnalysis
{
    /**
     * @param $data tcp消息
     * @param $stop 处理是否结束
     * @return array
     */
    public function process($data, &$stop)
    {
        $stop = true;
        return [
            "data" => $data,
            "router" => 10001,
        ];
    }
}