<?php
namespace core;
use \core\Container;
use \core\exception\ExceptionX;
use \core\leader\route\Route;
use \core\leader\database\DBOperate;
use \core\leader\config\Config;
use \core\leader\http\Request;
use \core\leader\http\Response;
use \core\leader\http\Cookie;
use \core\leader\view\View;
use \core\leader\Liquidator;
use \core\leader\ServiceInfo;
use \core\leader\middleware\Middleware;

class Kernel implements leader\Kernel{
    private Container $app;
    private Config $config;
    private Request $request;
    private Response $response;
    private Cookie $cookie;
    private Middleware $middleware;
    private Liquidator $liquidator;
    private View $view;
    private DBOperate $db;


    private function init(){
        ob_start();
        $that = $this;
        $this->app = app();

        #init Liquidator
        $this->liquidator = $this->app->make('liquidator');

        #init config
        $this->config = $this->app->make('config');

        # init session
        $this->app->bind(
            \core\leader\session\SessionStorage::class,
            $this->config->get('core.session.drive')
        );

        if ($this->config->get('core.session.enable',false)){
            $this->session = $this->app->make('session');
            $this->session->init($this->config);
            $this->liquidator->registerEvent(
                function() use ($that){
                    $that->session->save();
                }
            );
        }

        #init cookie
        $this->cookie = $this->app->make('cookie');
        $this->cookie->init();
        $this->liquidator->registerEvent(
            function() use ($that){
                $that->cookie->save();
            }
        );

        # init request
        $this->request = $this->app->make('request');
        $this->request->init($_SERVER);

        #view
        $this->view = $this->app->make(View::class);
        $this->liquidator->registerEvent(
            function() use ($that){
                $that->view->output();
            }
        );

        #init response
        $this->response = $this->app->make('response');
        $this->response->init();
        $this->liquidator->registerEvent(
            function() use ($that){
                $that->response->response();
            }
        );
    }

    public function start() : void{

        try {
            $this->init();

            $route = $this->app->make(Route::class);
            $matchresult = $this->app->call([$route,'match'],[
                $this->request->uri()->url(),
                $this->request->method()
            ]);

            if ($matchresult === null){
                $this->view->errorPage(404);
            }else{
                $this->request->setServiceInfo($matchresult);

                # 注入配置文件
                $this->config->loadServiceConfig(
                    pathjoin(
                        $this->request->serviceInfo()->getBasePath(),
                        "config.php"
                    )
                );
            
                # 初始化服务运行环境

                
                #init database
                $this->db = $this->app->make("db",[$matchresult->name()]);
        
                #middleware
                $this->middleware = $this->app->make('middleware');
                $this->app->call([$this->middleware,'init']);
                $run = $this->app->call([$this->middleware,'pre']);
        
                #run
                if ($run){
                    $runret = $this->run();

                    # 执行后置中间件函数
                    $this->app->call([$this->middleware,'post']);

                    # 使用view分析函数返回值，确定返回到前端的数据
                    $this->app->call([$this->view,'analysis'],[$runret]);
                }
            }
            

        }catch(ExceptionX $e){
            if (DEBUG){
                throw $e;
            }else{
                $this->view->errorPage(404);
            }
        }catch (\Exception $e) {
            if (DEBUG){
                throw $e;
            }else{
                $this->view->errorPage(404);
            }
        } finally{
            #结束请求
            $this->app->call([$this->liquidator,'callEvent']);
        }
    }

    /**
     * 调用目标应用函数
     */
    private function run(){

        $info = $this->request->serviceInfo();
        $param = $info->urlparam();
        $call = $info->call();

        if (is_string($call)){
            if (strpos($call,"view:") === 0){
                return substr($call,5);
            }
        }


        $ret = $this->app->call($call,$param);
        return $ret;
    }



}