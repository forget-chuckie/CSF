<?php

class DefaultAnalysis extends CoreAnalysis
{
    /**
     * @param $data tcp��Ϣ
     * @param $stop �����Ƿ����
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