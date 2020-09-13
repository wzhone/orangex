<?php
namespace core\leader;


interface ServiceInfo{

    public function __construct(        
        string $servicename,
        array $urlparam,
        string $wholerouteurl,
        array $routeitemdata
    );
    

    /**
     * 获取服务名
     * @return string
     */
    public function name() : string;

    /**
     * 获取匹配到的URL参数
     * @return array
     */
    public function urlparam() : array;

    /**
     * 获取route中匹配到的data数组里的数据项，不存在返回null
     * @param string          $name     数据项项名
     * @return mixed
     */
    public function __call(string $name,$nouse);


    /**
     * 获取服务文件夹所在物理路径
     * @return string
     */    
    public function getBasePath(): string;
    
}