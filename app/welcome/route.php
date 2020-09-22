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
            "call" => function(Container $app){
                return "page.welcome";
            },
            "middleware" => []
        ]
    ]



];