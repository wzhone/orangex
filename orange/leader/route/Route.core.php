<?php
namespace core\leader\route;

use \core\leader\http\Request;

interface Route{

    /**
     * 初始化路由
     * @return void
     */
    public function __construct();

    /**
     * 进行路由匹配
     * @param string          $facturl   请求的实际URL
     * @param string          $method    请求的方法
     * @param Request         $request   当前请求的request
     * @return ServiceInfo/NULL
     */
    public function match(string $facturl,string $method,Request $request);
    
}