<?php
namespace core\session;

class PHPSession implements \core\leader\session\SessionStorage{

    public function init(array $config): void{
        $name = dotq($config,'name',null);
        if ($name != null){
            if (is_numeric($name)){
                throw new \Exception("Problems with purely digital session names[$name]");
            }
            session_name(dotq($config,'name'));

            session_start($config);
        }

    }

    public function get() : array{
        return $_SESSION;
    }

    public function save(array $data) : void{
        $_SESSION = $data;
    }

}