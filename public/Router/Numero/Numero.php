<?php

include dirname(__DIR__).'/Router.Base.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Index routes and handling class
class Numero extends Router_Base
{
    public function __construct()
    {
    }

    public function home()
    {
        global $Core;

        $lView = $Core->View->loadView('Index', $this, $this->getConfig());

        if(!$lView)
            return;


        $lView->numero();

        echo $Core->VarBuilder->BuildElement($this->getConfig()->var, 'test', '', ['titre' => 'test', 'var' => 'variable_1']);
        echo '<br>';
        echo $Core->VarBuilder->BuildElement($this->getConfig()->var, 'test_2', 'test_titre', ['titre' => 'test', 'var' => 'variable_1']);
    }
}

