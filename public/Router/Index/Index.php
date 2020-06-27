<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Index routes and handling class
class Index
{
    public function __construct()
    {
    }

    public function home()
    {
        global $Core;

        $lView = $Core->View->loadView('Index');

        if(!$lView)
            return;

        $lView->home();
    }
}

