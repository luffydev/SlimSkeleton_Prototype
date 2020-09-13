<?php

global $Core;

// Router Base class

class Router_old
{
    private $mCurrentClass = '';
    private $mBaseDir;
    private $mConfig = null;

    public function __construct()
    {
        $this->mCurrentClass = get_called_class();
        $this->mBaseDir = dirname(__DIR__).'/Router';
    }

    public function loadRoute($pRoute, $pSubRoute = 'default')
    {
        global $Core;

        if(!file_exists($this->mBaseDir.'/'.$pRoute.'/'))
        {
            $Core->Logger->Write("Router", "Unable to find route : '".$pRoute."' abort.");
            return $this->LoadError('NotFound');
        }

        if(!file_exists($this->mBaseDir.'/'.$pRoute.'/config.json'))
        {
            $Core->Logger->Write("Router", "Unable to find config for route : '".$pRoute."' abort.");
            return $this->LoadError('NotFound');
        }

        //load our route PHP file
        $this->mConfig = json_decode(file_get_contents($this->mBaseDir.'/'.$pRoute.'/config.json'));


        if( !property_exists($this->mConfig,'routes') || !property_exists($this->mConfig->routes, $pSubRoute)) {
            $Core->Logger->Write("Router", "SubRoute '".$pSubRoute."' doesn't exist in config file abort !");
            return $this->LoadError('NotFound');
        }

        if(!$this->mConfig->routes->$pSubRoute->enabled)
        {
            $Core->Logger->Write("Router", "SubRoute '".$pSubRoute."' is disabled !");
            return $this->LoadError('NotFound');
        }

        include $this->mBaseDir.'/'.$pRoute.'/'.$pRoute.'.php';

        $lCurrentRoute = new $pRoute();
        $lCurrentRoute->init($this->mConfig, $this->mConfig->routes->$pSubRoute->name);

        if(!method_exists($lCurrentRoute, $this->mConfig->routes->$pSubRoute->name))
        {
            $Core->Logger->Write("Router", "Method '".$this->mConfig->routes->$pSubRoute->name." doesn't exist in class '".$pRoute."'");
            return $this->LoadError('NotFound');
        }

        $lCurrentRoute->{$this->mConfig->routes->$pSubRoute->name}();
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

$Core->push("Router_old", new Router_old());