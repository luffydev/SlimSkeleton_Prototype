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
        $lUri = explode('/', $lPath);

        if($lUri[1] == '')
            $lReturn = $Core->Router->loadRoute('Index');
        else {
            $lReturn = $Core->Router->loadRoute($lUri[1]);
        }

        if($lReturn)
            $response = $lReturn;
        else
            $response = $handler->handle($request);

        return $response;
    }
}

$Core->push('Middleware', new Middleware());