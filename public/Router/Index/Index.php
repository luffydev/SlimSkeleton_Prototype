<?php

include dirname(__DIR__).'/Router.Base.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Index routes and handling class
class Index extends Router_Base
{
    public function home()
    {
        global $Core;

        $lView = $Core->View->loadView('Index', $this, $this->getConfig());

        if(!$lView)
            return;

        $lView->home();
    }

    public function region()
    {
        global $Core;

        echo 'test';
    }
}

