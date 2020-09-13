<?php

include dirname(__DIR__).'/Router.Base.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Contact routes and handling class
class Html extends Router_Base
{
    public function home()
    {
        global $Core;
     
        /*$test = $Core->Model->load('Utils');
        $test->checkHasURL("Bonjour je m'appel jérémy comment ça va ?");
        */
       
        $lView = $Core->View->loadView('Index', $this, $this->getConfig());

        if(!$lView)
            return;

        $lView->html();
       
        
        
    }

   
}

