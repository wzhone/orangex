<?php
define("DEBUG",true);

$GLOBALS['_beginTime'] = microtime(TRUE);
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();
define("BASEPATH",dirname(__FILE__)."/"); //项目根目录位置
//file location
define("CORE",BASEPATH."orange/");
define("COMMON",BASEPATH."common/");
define("RUNTIME",BASEPATH."runtime/");
define("APP",BASEPATH."app/");
define("CONFIG",COMMON."config/");
define("VENDER",COMMON."vender/");
define("LOG",RUNTIME."log/");
define("CACHE",RUNTIME."cache/");
define("SUPPORT",CORE."support/");

require_once(CORE.'AutoLoader.core.php');
require_once(CORE.'Functions.core.php');
require_once(CORE.'Container.core.php');
require_once(CORE.'Bootstrap.core.php');


// ini_set('session.cookie_path', '/');
// ini_set('session.cookie_lifetime', config("base.timeout"));
// ini_set('date.timezone','Asia/Shanghai');

// if (config("base.domain")!==null){
// 	ini_set("session.cookie_domain",config("base.domain"));
// }
// ini_set("session.name",config("base.sessname","PHPSESSID"));
// 

//启动框架


/*
    数据库

    聚合函数支持不好
    update不支持使用自己的参数进行处理
    只支持inner join
	
	session
	不支持数据库session 只支持文件session

	view模块
	多方面支持不完善

	Log模块
	没写

	Exception
	没写

*/