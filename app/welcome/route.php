<?php
return [
    "info" =>[
        "prefix" => "/welcome"
        
    ],
    "routes" =>[
        [
            "url" => "/",
            "method" => "get",
            "call" => function(){
                return "page.welcome";
            },
            "middleware" => []
        ]
    ]



];