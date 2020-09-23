<?php


use \core\Container;

return [
    "info" =>[
        "prefix" => "/welcome"
        
    ],
    "routes" =>[
        [
            "url" => "/",
            "method" => "get",
            "call" => "view:page.welcome",
            "middleware" => []
        ]
    ]



];