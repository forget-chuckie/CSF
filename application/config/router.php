<?php

$config["analysis_routes"] = [
    "DefaultAnalysis",
];

$config["receive_routes"] = [
    "10001" => "ActionExp",
];

$config["send_routes"] = [
    "normal" => [
        "QueuePush",
    ],
    "loop" => [
        "RedisPush",
    ],
];