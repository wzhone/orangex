<?php
namespace app\test;

use \core\Container;
use \core\leader\http\Cookie;
use \core\leader\config\Config;
use \core\leader\http\Response;
use core\leader\session\SessionOperate;


class midd2{
    public function preHandle(
        Container $app,
        Config $config,
        SessionOperate $session,
        Cookie $cookie,
        Response $response){
            

        #开始测试
        if (@file_get_contents(pathjoin(BASEPATH,".test"))!="pass step 1"){
            echo "未通过测试一，请转向第一测试";
            return false;
        }

        # 测试session(step 2)
        assert($session->has("key1") == true);
        assert($session->get("key1") == "value1");
        assert($session->pull("key1") == "value1");
        assert($session->pull("key1","none") == "none");
        assert($session->get("key1","none") == "none");
        assert($session->has("key1") == false);

        assert($session->get("flashkey1") == "value1");
        assert($session->get("flashkey1") == null);

        assert($session->get("key2") == $session->all()["key2"]);
        $session->delete();
        assert(count($session->all()) == 0);

        # 测试Cookie(step 2)
        assert($cookie->has("key1"));
        assert($cookie->get("key1") == "value1");
        assert($cookie->all()["key1"] == "value1");
        assert($cookie->pull("key1") == "value1");

        
        if (\ob_get_contents()!=""){
            echo "测试二有意外输出";
        }else{
            $response->redirect("/step3");
        }

        file_put_contents(pathjoin(BASEPATH,".test"),"pass step 2");
        return false;
    }


    public function postHandle(){
        assert(false);
        /*
            前置中间件返回了false，所以不会执行这里
        */
        return true;  
    }

}