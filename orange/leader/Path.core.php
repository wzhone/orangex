<?php
namespace core\leader;


interface Path{

    //public function __construct();
    

    /**
     * 拼接目录
     * @param string        $path     路径
     * @return string
     */
    public function join(...$path) : string;

    /**
     * 获取核心目录，传入一个路径进行拼接
     * @return string
     */
    public function core_path(...$path) : string;
    
    /**
     * 获取公共目录，传入一个路径进行拼接
     * @return string
     */
    public function common_path(...$path) : string;

    /**
     * 获取临时目录，传入一个路径进行拼接
     * @return string
     */
    public function runtime_path(...$path) : string;

    /**
     * 获取配置文件目录，传入一个路径进行拼接
     * @return string
     */
    public function config_path(...$path) : string;

    /**
     * 获取应用目录，传入一个路径进行拼接
     * @return string
     */
    public function app_path(...$path) : string;

    /**
     * 获取缓存目录，传入一个路径进行拼接
     * @return string
     */
    public function cache_path(...$path) : string;

    /**
     * 获取核心支持目录，传入一个路径进行拼接
     * @return string
     */
    public function support_path(...$path) : string;
    
    /**
     * 获取服务公开目录路径，传入一个路径进行拼接
     * @return string
     */
    public function public_path(...$path) : string;

}