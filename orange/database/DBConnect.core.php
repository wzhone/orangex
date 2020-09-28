<?php
namespace core\database;
use \core\Container;
use \core\leader\config\Config;
use PDOStatement;
use PDO;

class DBConnect implements \core\leader\database\DBConnect{

    private PDO $pdo;
    private PDOStatement $statement;
    private string $sql;

    public function init(string $linkname) : void{
        $config = config("core.database.$linkname");

        $port=    $config['port'];
        $host=    $config['host'];
        $charset= $config['charset'];
        $pwd=     $config['password'];
        $username=$config['username'];
        $dbname=  $config['database'];

        try{
            $dsn=$config["drive"].":port=$port;host=$host;dbname=$dbname;charset=$charset";
            $this->pdo=new \PDO($dsn,$username,$pwd);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); //禁用模拟预处理，防止sql注入
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $e){
            if (DEBUG){
                $errmsg = '数据库错误: ' . $e->getMessage() . '<br/>';
                echo $errmsg;
            }
            throw $e;
        }
    }

    public function setsql(string $sql) : PDOStatement{
        $this->sql = $sql;
        $this->statement=$this->pdo->prepare($sql);
        return $this->statement;
    }

    public function bind(string $para,string $val,bool $filter=false) : void{
        if ($filter) $val=addslashes($val);
        $this->statement->bindValue($para,$val);
    }
    
    public function exec(array $input = []) : bool{
        try{
            return $this->statement->execute($input);
        }catch(\PDOException $e){
            if (DEBUG){
                $this->errorInfo();
                echo $this->sql;
            }
            throw $e;
            return false;
        }
    }

    public function errorInfo() : array{
        return $this->pdo->errorInfo();
    }

    public function fetchAll($fetchstyle = \PDO::FETCH_ASSOC){
        return $this->statement->fetchAll($fetchstyle);
    }

    public function fetch($fetchstyle = \PDO::FETCH_ASSOC){
        return $this->statement->fetch($fetchstyle);
    }

    #返回DELETE、 INSERT、或 UPDATE 语句受影响的行数
    public function rowCount() : int{
        return $this->statement->rowCount();
    }
    
    public function getPDO() : \PDO{
        return $this->pdo;
    }
    public function getStatement() : \PDOStatement{
        return $this->statement;
    }

    public function beginTransaction() : bool{
        return $this->pdo->beginTransaction();
    }
    
    public function commit() : bool{
        return $this->pdo->getPDO()->commit();
    }
    
    public function rollback() : bool{
        return $this->pdo->getPDO()->rollBack();
    }

}