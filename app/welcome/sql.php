<?php
return [

    "getUsers" => [
        'sql' => "select * from `uce.user` where username = ?",
        'ret' => "list"
    ],

    "getPassword" => [
        'sql' => "select * from user",
        'ret' => "class"
    ],

    "addUser" => [
        'sql' => "insert into user",
        'ret' => "none"
    ],


];