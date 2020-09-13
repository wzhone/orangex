<?php
namespace core\config;

class Config implements \core\leader\config\Config{
    
    private $env = [];
    private $config = [];

    public function __construct(){
        $this->readenv();
    }

    public function get(string $str,$default = null){
        $env = $this->getEnv($str,null);
        if ($env == null)
            return $this->getConfig($str,$default);
        else
            return $env;
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

    # 从硬盘上读取env文件
    private function readenv(){
        $file = pathjoin(BASEPATH ,'.env');
        if(file_exists($file)){
            $this->env = require $file;
        }else{
            $this->env = [];
        }
    }

    # 从硬盘上读取指定config文件
    private function readconfig($name){
        $file = CONFIG . "$name.config.php";
        if(file_exists($file)){
            $this->config[$name] = require $file;
        }else{
            throw new \Exception("Config [$name] not exist");
        }
    }

}