<?php
namespace app\test;

use \core\leader\http\Request;
use \core\leader\http\Response;
use \core\leader\config\Config;
use \core\leader\database\DBOperate;
use \core\Container;

class Test{


    public function run(Container $app){
        // $db = DB();
        // var_dump($db->getUsers(["name"]));
        return "testpage";
    }

    public function ajax(Request $request){
        if (($request->has("test","testid")) && 
        (!$request->has("test1","testid1")) &&
        ($request->method() == "post") &&
        ($request->input("test") == "ajax") &&
        ($request->input("testid") == "1")){
            return ["state"=>true];
        }else{
            return ["state"=>false];
        }
    }
    public function param(Request $request,$id,Config $config){
        if ( !$request->has("test") ||
              $request->input("test") != "param"
        ){
            return ["state"=>false];
        }
        if ($id == $config->getConfig("core.viewglobal.VERSION")){
            return ["state"=>true];
        }else{
            return ["state"=>false];
        }
    }
    public function headertest(Request $request,Response $response){
        if ($request->header("testtype") == "header"){
            $response->header("result","success");
            $response->header(
                ["version"=>config()->getConfig("core.viewglobal.VERSION")]
            );
            return ["state"=>true];
        }else{
            return ["state"=>false];
        }
    }


}