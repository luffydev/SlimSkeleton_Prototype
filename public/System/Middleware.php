<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

// Middleware Class

class Middleware
{
    public function __construct()
    {
    }

    public function __invoke(Request $request, RequestHandler $handler):Response
    {
        global $Core;
       
        $lPath = $request->getUri()->getPath();
        $lUri = $_SERVER['REQUEST_URI'];
     
        if($lUri == '')
            $lReturn = $Core->Router->loadRoute('Index');
        else {
            
            $lReturn = $Core->Router->loadRoute($lUri);
        }

        if($lReturn)
            $response = $lReturn;
        else
            $response = $handler->handle($request);

        return $response;
    }
}

$Core->push('Middleware', new Middleware());