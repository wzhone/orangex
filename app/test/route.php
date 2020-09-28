<?php

use \core\leader\http\Response;

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_BAIL, 1);
error_reporting(E_ALL);


return [
    "info" =>[
        "prefix" => "",
    ],
    "routes" =>[
        [
            "url" => ["/","/step1"],
            "method" => "get",
            "call" => function(Response $response){
                return true;
            },
            "middleware" => [
                "\app\\test\midd1"
            ]
        ],
        [
            "url" => "/step2",
            "method" => "get",
            "call" => function($response){
                assert(false); //因为中间件返回false,所以不应该运行到这里
                return false;
            },
            "middleware" => [
                '\app\test\midd2',
            ]
        ],
        [
            "url" => "/step3",
            "method" => "get",
            "call" => '\app\test\Test@run',
            "middleware" => [
                '\app\test\midd3'
            ]
        ],
        [
            "url" => "/step3",
            "method" => "post",
            "call" => '\app\test\Test@ajax',
        ],
        [
            "url" => "/test/param/{id}",
            "method" => "post",
            "call" => '\app\test\Test@param',
        ],
        [
            "url" => "/test/headertest",
            "method" => "put",
            "call" => '\app\test\Test@headertest',
        ]
    ]



];