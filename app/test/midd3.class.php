<?php
namespace app\test;

use \core\Container;
use \core\leader\http\Cookie;

class midd3{
    public function preHandle(
        Container $app,
        Cookie $cookie){

        # 开始测试
        if (@file_get_contents(pathjoin(BASEPATH,".test"))!="pass step 2"){
            echo "未通过测试二，请转向第二测试";
            return false;
        }

        # 测试Cookie(step 3)
        assert($cookie->has("key1") == false);


        return true;
    }


    public function postHandle(){
        unlink(pathjoin(BASEPATH,".test"));
        return true;  
    }

}