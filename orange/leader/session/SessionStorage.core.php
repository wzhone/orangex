<?php
namespace core\leader\session;

use \core\leader\config\Config;

interface SessionStorage{

    /**
     * 初始化Session存储驱动
     * @param array         $config     相应的配置信息 core.session.data
     * @return void
     */
    public function init(array $config): void;

    /**
     * 获取所有数据
     * @return array
     */
    public function get() : array;


    /**
     * 设置数据
     * @param array     $data   需要存储的数据
     * @return void
     */
    public function save(array $data) : void;

}