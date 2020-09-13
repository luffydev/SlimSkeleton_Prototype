<?php

global $Core;

Class View
{
    private $mBaseDir = '';

    public function __construct()
    {
        $this->mBaseDir = __DIR__.'/../View';
    }

    public function loadView($pName, $pRoute, $pConfig)
    {
        global $Core;

        if(!file_exists($this->mBaseDir.'/'.$pName.'.php'))
        {
            $Core->Logger->Write('View', 'Unable to find View '.$pName);

            //TODO : debug this
            $Core->Router->loadError('NotFound');

            return;
        }

        include $this->mBaseDir.'/'.$pName.'.php';

        $lClass = $pName.'_View';

        $lClassPtr = new $lClass();

        $lClassPtr->setConfig($pConfig);
        $lClassPtr->setRoute($pRoute);

        //Load view Resources
        $Core->ResourceBuilder->BuildCSS($Core->GetCurrentRoute());
        $Core->ResourceBuilder->BuildJS($Core->GetCurrentRoute());

        //Display our Resources
        echo $Core->ResourceBuilder->GetBuildedCSS();
        echo $Core->ResourceBuilder->GetBuildedJS();
      
        return $lClassPtr;
    }
}

$Core->push('View', new View());