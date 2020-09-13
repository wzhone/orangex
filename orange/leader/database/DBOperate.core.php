<?php
namespace core\leader\database;
use \core\Container;

interface DBOperate{


    /**
     * 初始化DBOperate类
     * @param string        $sqlfilename 服务名
     * @return void
     */
    public function __construct(string $sqlfilename,Container $app);


    /**
     * 获取DBConnect类
     * @return DBConnect
     */
    public function getConnect();

    /**
     * 启动一个事务
     * @return bool
     */
    public function beginTransaction() : bool;


    /**
     * 提交一个事务
     * @return bool
     */
    public function commit() : bool;
    

    /**
     * 回滚一个事务
     * @return bool
     */   
    public function rollback() : bool;



    /**
     * 调用Sql语句方法，返回null代表查询为空
     * @param string       $func  函数名
     * @param array        $param 参数
     * @return mixed
     */
    public function __call(string $func,array $param);

}