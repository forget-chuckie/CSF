<?php
$config["connect_routes"] = [
    "DefaultConnect",
];

$config["analysis_routes"] = [
    "DefaultAnalysis"
];

$config["receive_routes"] = [
    10001 => "DefaultAction"
];

$config["send_routes"] = [
    "normal" => [
        "DefaultConsumer"
    ],
    "loop" => [
        "DefaultPush",
    ],
];


$config["close_routes"] = [
    "DefaultClose",
];