<?php
namespace app\test;

use \core\Container;
use \core\leader\ServiceInfo;
use \core\leader\http\Cookie;
use \core\leader\config\Config;
use \core\leader\http\Request;
use \core\leader\http\Response;
use core\leader\session\Session;

class midd1{


    public function preHandle(
        Container $app,
        Config $config,
        Session $session,
        Request $request,
        Cookie $cookie){

        #开始测试
        @unlink(pathjoin(BASEPATH,".test"));


        # 测试ServiceInfo
        $info = $request->serviceInfo();
        assert($info->name() == "test");

        # 测试配置类
        $configfile =  common_path("config","test.config.php");
        file_put_contents($configfile,'<?php return ["test"=>["dot"=>["key"=>"value","key2"=>"value2"]]];');

        $cenv = $ReflectionConfig = (new \ReflectionClass($config))->getProperty("env"); 
        $cenv->setAccessible(true);
        $cenv->setValue($config,[ 
            "test" =>[
                "test"=>[
                    "dot"=>[
                        "key"=>"envvalue"
                    ],
                    "allcover" => "true"
                ]
            ]
        ]);

        assert($config->get("test.test.dot.key") == "envvalue");
        assert($config->getConfig("test.test.dot.key") == "value");
        assert($config->get("test.test.dot.keynone") == null);
        assert($config->getConfig("test.test.dot.keynone") == null);

        # 数组值获取
        assert($config->get("test.test.dot")["key"] == "envvalue");
        assert($config->get("test.test.dot")["key2"] == "value");

        # 数组覆盖
        assert($config->get("test.test.allcover") == "true");  

        unlink($configfile);

        # 测试session(step 1)
        $session->delete();
        assert($session->has("key1") == false);
        $session->put("key1","value1");
        $session->put("key2","value2");
        $session->flash("flashkey1","value1");

        # 测试Cookie(step 1)
        $cookie->put("key1","value1");
        return true;
    }


    public function postHandle(Response $response){
        file_put_contents(pathjoin(BASEPATH,".test"),"pass step 1");

        if (\ob_get_contents()!=""){
            echo "测试一有意外输出";
        }else{
            $response->redirect("/step2");
        }
        return true;  
    }

}