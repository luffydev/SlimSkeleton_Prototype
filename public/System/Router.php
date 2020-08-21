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

            if(!property_exists($lConfig, $pRoute))
            {
                $Core->Logger->Write("Router", "Unable to find route ".$pRoute." in routes.json. abort.");
                return $this->LoadError('NotFound');
            }

            include $this->mBaseDir.'/'.$pRoute.'/'.$pRoute.'.php';

            $lCurrentRoute = new $pRoute();
            $lCurrentRoute->init($lConfig->$pRoute, 'home');
    
            $lCurrentRoute->home();
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