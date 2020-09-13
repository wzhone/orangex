<?php
namespace core\database;

use \core\Container;
use \core\leader\config\Config;
use \core\leader\database\DBConnect;
use \core\exception\ExceptionX;

class DBOperate implements \core\leader\database\DBOperate{
    public const fetch_assoc = \PDO::FETCH_ASSOC;
    public const fetch_num = \PDO::FETCH_NUM;
    public const fetch_obj = \PDO::FETCH_OBJ;

    private $nowlinkname = "";
    private DBConnect $dbconnect;

    private array $sqldata = [];
    private string $sqlfilename;


    public function __construct(string $sqlfilename,Container $app){
        $this->sqlfilename = $sqlfilename;

        $this->sqldata = require pathjoin(APP,$this->sqlfilename,'/sql.php');
    }

    public function getConnect() : DBConnect{
        return $this->dbconnect;
    }

    /*
        现在用户想用事务等功能，就需要先手动连接才行，
        如果手动连接了，执行了别的连接，事务还会中断
    */
    public function beginTransaction() : bool{
        return $this->dbconnect->beginTransaction();
    }

    public function commit() : bool{
        return $this->dbconnect->commit();
    }

    public function rollback() : bool{
        return $this->dbconnect->rollBack();
    }

    public function connect(string $configname = "default"){
        $config = config("core.database.$configname");
        if ($config === null){
            throw new \Exception("不存在的数据库连接 [$configname]");
        }
        $this->dbconnect = app()->make(DBConnect::class);
        $this->dbconnect->init($configname);
        $this->nowlinkname = $configname;
    }


    //返回null代表为空
    public function __call(string $func,array $param){
        $data = $this->sqldata[$func] ?? null;
        if ($data == null){
            throw new \ExceptionX('sql.php不存在此函数');
        }

        # 判断是否是正确的连接
        $link = $data['link'] ?? "default";
        if ($link != $this->nowlinkname){
            $this->connect($data['link']);
        }

        # 参数处理
        if (count($param)!=0)
            $param = $param[0];

        if (!isset($data['ret'])) $data['ret'] = 'list';

        if (($data['ret']) == 'class'){
            $data['sql'] .= ' limit 1';
        }

        $c = $this->dbconnect;
        $c->setsql($data['sql']);

        $c->exec($param);

        switch($data['ret']){
            case 'none':  return $c->rowCount();
            case 'class':
                $ret = $c->fetch(self::fetch_obj);
                if (!$ret)
                    return null;
                else
                    return $ret;
                    
            case 'array':  
                $ret = $c->fetchAll(self::fetch_assoc);
                if (count($ret) == 0)
                    return null;
                else
                    return $ret;

            default:
            case 'list':  
                $ret = $c->fetchAll(self::fetch_obj);
                if (count($ret) == 0)
                    return null;
                else
                    return $ret;
        }
    }

}