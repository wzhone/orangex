<?php
return [
    'dependency' => [
        ['core\leader\Kernel','core\Kernel','singleton'],
        ['core\leader\config\Config','core\config\Config','singleton'],

        #base
        ['core\leader\config\Config','core\config\Config','singleton','config'],
        ['core\leader\session\SessionOperate','core\session\Session','singleton','session'],
        ['core\leader\http\Cookie','core\http\Cookie','singleton','cookie'],
        ['core\leader\http\Request','core\http\Request','singleton','request'],
        ['core\leader\http\Response','core\http\Response','singleton','response'],
        ['core\leader\middleware\Middleware','core\middleware\Middleware','singleton','middleware'],
        ['core\leader\Liquidator','core\Liquidator','singleton','liquidator'],
        ['core\leader\view\View','core\view\View','singleton','view'],
        ['core\leader\database\DBOperate','core\database\DBOperate','singleton',"db"],

        #support
        ['core\leader\http\Uri','core\http\Uri','bind'],
        ['core\leader\database\DBConnect','core\database\DBConnect','bind'],
        ['core\leader\route\Route','core\route\Route','bind'],
        ['core\leader\ServiceInfo','core\ServiceInfo','bind'],
        


    ]


];