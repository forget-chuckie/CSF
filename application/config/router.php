<?php
$config["connect_routes"] = [
    "DefaultConnect",
];

$config["analysis_routes"] = [
    "DefaultAnalysis"
];

$config["receive_routes"] = [
    "ActionExp"
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