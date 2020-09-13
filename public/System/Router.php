<?php

    // Router base class

    class Router
    {
        private $mCurrentClass = '';
        private $mBaseDir;
        private $mConfig = null;

        public function __construct()
        {
            $this->mCurrentClass = get_called_class();
            $this->mBaseDir = dirname(__DIR__).'/Router';
        }

        public function loadRoute($pRoute)
        {
            global $Core;

            $lConfigDir = dirname(__DIR__).'/routes.json';

            $pRoute = strtolower($pRoute);

            if(!file_exists($lConfigDir))
            {
                $Core->Logger->Write("Router", "Unable to locate file routes.json. abort.");
                return $this->LoadError('NotFound');
            }

            $lConfig = json_decode(file_get_contents($lConfigDir));
            
            $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use($lConfig){
                //print_r($lConfig);
                foreach ($lConfig as $key => $config_array) {
                    $url = $config_array->url;
                    $httpMethod = $config_array->method ?? 'GET';
                    $r->addRoute('GET', $url, $key);
                }
              
            });
            $httpMethod = $_SERVER['REQUEST_METHOD'];
            
            $routeInfo = $dispatcher->dispatch($httpMethod, $pRoute);
            $pRoute = $routeInfo['1'] ?? '';
           
            if(!property_exists($lConfig, $pRoute))
            {
                $Core->Logger->Write("Router", "Unable to find route ".$pRoute." in routes.json. abort.");
                return $this->LoadError('NotFound');
            }
            $lConfig->$pRoute->html = $lConfig->$pRoute->html ?? FALSE;
           
            if($lConfig->$pRoute->html == 'TRUE'){
            
                $pController = $this->mBaseDir.'/Html/Html.php';
               
            }
            else {
                $pController = $this->mBaseDir.'/'.ucfirst($pRoute).'/'.ucfirst($pRoute).'.php';
            }
            
          
            if(!file_exists($pController)){
               
                $Core->Logger->Write("Router", "Unable to find file $pController ");
                return $this->LoadError('NotFound');
            }
            include $pController;
            if($lConfig->$pRoute->html == 'TRUE'){
               
                $lCurrentRoute = new Html();
                $lCurrentRoute->init($lConfig->$pRoute, 'home');
                $lCurrentRoute->home();
            }
            else {
                
                $lCurrentRoute = new $pRoute();
                $lCurrentRoute->init($lConfig->$pRoute, 'home');
        
                $lCurrentRoute->home();
            }
            $Core->Template->setVar('page', $pRoute);
            $coin_info = json_decode(file_get_contents("https://static.coinpaper.io/api/coins/lend-ethlend.json"));
            if (strpos($coin_info->market->price_24h_percentage_change, '-') !== false) {
                $coin_info->color = 'danger';
            }
            else {
                $coin_info->color = 'success';
            }

            $top_5 = json_decode(file_get_contents("https://static.coinpaper.io/api/coins.json"));
            $Core->Template->setVar('coin_info', $coin_info);
            foreach ($top_5 as $key => $v) {
                if($v->rank < 5){
                   
                    $final_top[] = $v;
                }
               $percentage = $v->price_24h_percentage_change;
                if (strpos($percentage, '-') !== false) {
                    $v->color = 'danger';
                }
                else {
                    $v->color = 'success';
                }
            }
            $Core->Template->setVar('top_5', $final_top);
          

           
        }

        public function getRouteConfig($pName)
        {
            $lConfigDir = dirname(__DIR__).'/routes.json';
            $pRoute = strtolower($pRoute);

            if(!file_exists($lConfigDir))
            {
                $Core->Logger->Write("Router", "Unable to locate file routes.json. abort.");
                return;
            }

            $lConfig = json_decode(file_get_contents($lConfigDir));

            if(!property_exists($lConfig, $pName))
            {
                $Core->Logger->Write("Router", "Unable to find route config ".$pName." in routes.json. abort.");
                return;
            }

            return $lConfig->$pName;
        }

        public function loadError($pError)
        {
            
            include $this->mBaseDir.'/Errors/Error.php';

            $lPage = new ErrorRoute();

            if(method_exists($lPage, $pError)) {
            return $lPage->$pError();
            }
        }
    }

    $Core->push("Router", new Router());
?>