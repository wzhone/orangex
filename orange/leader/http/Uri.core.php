<?php
namespace core\leader\http;

interface Uri{

    public function __construct(array $server);

    /**
     * 获取不含get的请求url
     * @return string
     */
    public function url() : string;


    /**
     * 获取原生GET请求的参数
     * @return string
     */
    public function queryString() : string;


    /**
     * 获取原生请求的url
     * @return string
     */
    public function requestUrl() : string;


    /**
     * 获取请求使用的域名
     * @return string
     */
    public function domain() : string;



}

