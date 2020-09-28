<?php
namespace core\config;

class Config implements \core\leader\config\Config{
    
    private $env = [];
    private $config = [];
    private $serverconfig = [];

    public function __construct(){
        $this->readenv();
    }

    public function loadServiceConfig(string $path) : bool{
        if (file_exists($path)){
            $this->serverconfig = require $path;
            return true;
        }else{
            $this->serverconfig = [];
            return false;
        }
    }

    public function get(string $str,$default = null){
        
        $env = $this->getEnv($str,[]);
        $service = $this->getServiceConfig($str,[]);
        $config = $this->getConfig($str,[]);

        if (is_array($env) && is_array($service) && is_array($config)){
            /*
                如果三个变量都是数组则有两种可能
                1.取值均为空，此时应该返回默认值
                2.取出来的有效值都是数组，此时应该根据优先级一次合并数组
                  这样可以实现其他配置文件只覆盖一个数组中的部分项

                如果取出来的只有部分数组，则说明有指明了配置项的覆盖。
                此时应依照优先级进行返回
            */
            if (count($env)==0 && count($service)==0 && count($config)==0)
                return $default;
            else
                return array_merge($config,$service,$env);
        }

        if (!is_array($env) || count($env)!=0 ) return $env;
        if (!is_array($service) || count($service)!=0 ) return $service;
        if (!is_array($config) || count($config)!=0 ) return $config;
        return $default;
    }

    # 使用点分字符串获取配置文件
    public function getConfig(string $str,$default = null){
        $file = explode('.',$str,2)[0];
      
        if ($this->config[$file] ?? true){
            $this->readconfig($file);
        }
        return \dotq($this->config,$str,$default);
    }

    # 使用点分字符串获取环境变量
    public function getEnv(string $str,$default = null){
        return \dotq($this->env,$str,$default);
    }

    # 从服务配置文件读取配置项
    public function getServiceConfig(string $str,$default = null){
        return \dotq($this->serverconfig,$str,$default);
    }

    # 从硬盘上读取env文件
    private function readenv(){
        $file = pathjoin(BASEPATH ,'.env');
        if(file_exists($file)){
            $this->env = require $file;
        }else{
            $this->env = [];
        }
    }

    # 从配置文件目录上读取指定config文件
    private function readconfig($name){
        $file = config_path("$name.config.php");
        if(file_exists($file)){
            $this->config[$name] = require $file;
        }else{
            $this->config[$name] = [];
        }
    }

}