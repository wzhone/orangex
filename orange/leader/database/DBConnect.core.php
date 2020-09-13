<?php
namespace core\leader\database;
use \core\Container;
use \core\leader\config\Config;
use PDOStatement;
use PDO;


interface DBConnect{


    /**
     * 初始化一个数据库连接
     * @param string        $linkname   配置文件中已经配置好的连接名称
     * @return void
     */
    public function init(string $linkname) :  void;


    /**
     * 设置一个sql语句
     * @param string        $sql   要设置的sql语句
     * @return PDOStatement
     */
    public function setsql(string $sql) : PDOStatement;


    /**
     * 绑定一个sql语句的参数
     * @param string        $para   参数名
     * @param string        $val    要绑定的值，非引用绑定
     * @param bool          $filter 启用过滤器，防止sql注入
     * @return void
     */
    public function bind(string $para,string $val,bool $filter=false) : void;


    /**
     * 执行sql语句，返回执行结果
     * @param array        $input   sql语句参数数组
     * @return bool
     */
    public function exec(array $input) : bool;


    /**
     * 返回上一次执行的语句的错误信息
     * @return array
     */
    public function errorInfo() : array;


    /**
     * 返回DELETE、 INSERT、或 UPDATE 语句受影响的行数
     * @return int
     */
    public function rowCount() : int;
    

    /**
     * 返回一个包含结果集中所有行的数组 
     * @return mixed
     */
    public function fetchAll($fetchstyle = \PDO::FETCH_ASSOC);


    /**
     * 从结果集中获取下一行 
     * @return mixed
     */
    public function fetch($fetchstyle = \PDO::FETCH_ASSOC);


    /**
     * 获取PDO
     * @return PDO
     */
    public function getPDO() : PDO;


    /**
     * 获取PDOStatement
     * @return PDOStatement
     */
    public function getStatement() : PDOStatement;
    

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


}