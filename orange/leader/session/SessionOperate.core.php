<?php
namespace core\leader\session;

use \core\leader\config\Config;

interface SessionOperate{


    /**
     * 初始化Session
     * @return void
     */
    public function init() : void;


    /**
     * 通过驱动保存session值
     * @return void
     */
    public function save() : void;


    /**
     * 设置session值
     * @param string         $key      session键名
     * @param mixed          $name     session值
     * @return void
     */
    public function put(string $key,$value) : void;
    

    /**
     * 设置一次性session值
     * @param string         $key      session键名
     * @param mixed          $name     session值
     * @return void
     */
    public function flash(string $key,$value) : void;
    
    
    /**
     * 获取一个session值
     * @param string         $key      session键名
     * @param mixed          $default  默认值
     * @return mixed
     */
    public function get(string $key,$default);
    
    
    /**
     * 获取并删除一个session值
     * @param string         $key      session键名
     * @param mixed          $default  默认值
     * @return mixed
     */
    public function pull(string $key,$default);
    
    
    /**
     * 判断session中某个值是否存在
     * @param string         $key      session键名
     * @return bool
     */
    public function has(string $key):bool;


    /**
     * 获取所有session数据
     * @return array
     */
    public function all() : array;
    
    
    /**
     * 删除所有session数据
     * @return void
     */
    public function delete() : void;
    
}

class SessionFlash{
    public function __construct($value){
        $this->value = $value;
    }
    public $value;
}