<?php
return [

    "area" => "zh-CN",
    "session" =>[
        'enable' => true,                           # 是否启用session
        "drive" => "core\session\PHPSession",       # session存储引擎
        'data' =>[
            'name' => 'orange',
        ]
    ],

    "cookie" =>[

    ],

    "database" =>[
        "default" => [
            "drive"=>"mysql",
            "host" => "127.0.0.1",
            "port" => "3306",
            "database" => "wzhyun",
            "username" => "root",
            "password" => "root",
            "charset"  =>"utf8mb4"
        ],
        "user" => [
        ],
        "other" =>[
        ]
    ],

    "globalMiddleware" => [],
    "middlewareGroup" =>[],
    "viewglobal" =>[
        "VERSION" => "OrangeX",
        "jquery" => "/public/js/jquery.min.js",
    ],
    "viewpath" => "/view",
    // "errorpagepath" => null,



];