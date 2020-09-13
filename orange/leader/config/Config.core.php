<?php
namespace core\leader\config;

/**
 * 配置的加载采用动态加载，需要对应文件时才加载文件
 * 但是.env文件每次都会加载
 */
interface Config{
    public function __construct();

    /**
     * 获取一个配置项
     * @param string         $str     点分格式的配置项
     * @param mixed          $default 默认值
     * @return mixed
     */
    public function get(string $str,$default = null);


    /**
     * 获取一个配置项，仅从配置文件读取
     * @param string         $str     点分格式的配置项
     * @param mixed          $default 默认值
     * @return mixed
     */
    public function getConfig(string $str,$default = null);


    /**
     * 获取一个配置项，仅从环境文件读取
     * @param string         $str     点分格式的配置项
     * @param mixed          $default 默认值
     * @return mixed
     */
    public function getEnv(string $str,$default = null); 
}