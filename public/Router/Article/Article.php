<?php

include dirname(__DIR__).'/Router.Base.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Index routes and handling class
class Article extends Router_Base
{
    public function __construct()
    {
    }

    public function home()
    {
        global $Core;
       
        $lView = $Core->View->loadView('Blog', $this, $this->getConfig());

        if(!$lView)
            return;


        $lView->article();

        //echo $Core->VarBuilder->BuildElement($this->getConfig()->var, 'test', '', ['titre' => 'test', 'var' => 'variable_1']);
        
    }
}

