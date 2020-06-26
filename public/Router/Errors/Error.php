<?php
use Slim\Psr7\Response;

global $Core;

class ErrorRoute
{
    public function __construct()
    {
    }

    // 404 Page
    public function NotFound()
    {
        global $Core;

        $lResponse = new Response();
        $Core->setContext($lResponse);

        $Core->Template->parseTemplate('Errors/404.twig');

        return $lResponse;

    }
}

