<?php
namespace core\leader\view;

use \core\leader\config\Config;
use \core\leader\http\Response;
use \core\leader\http\Request;

interface View{

    public function __construct(Config $config,Request $request);

    /**
     * 向前端返回一个页面,页面路径基于服务路径和 core.viewpath 组合而成
     * @param string         $file   文件名，可以使用点分来实现多层目录
     * @param array          $param  附加参数
     * @return void
     */
    public function display(string $file,array $param = []) : void;

    
    /**
     * 绑定一个参数
     * @param mixed         $param   要绑定的参数名，可以是字符串或者是关联数组
     * @param mixed         $value   要绑定参数的值
     * @return void
     */
    public function bind($param,$value = null) : void;


    /**
     * 通过智能推断决定返回的数据
     * 1. 纯数字返回对应数字的错误页
     * 2. 字符串和对象会被解析为json格式
     * @param mixed         $s         需要分析的要返回的数据
     * @return void
     */
    public function analysis($s) : void;
    
    /**
     * 输出view数据
     * @return void
     */
    public function output() : void;


    /**
     * 显示错误页面
     * @param string       $status  错误码
     * @return void
     */
    public function errorPage(string $status) : void;
}