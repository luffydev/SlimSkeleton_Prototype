<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Index_View
{
    public function __construct()
    {
    }

    public function home()
    {
        global $Core;
        $Application = $Core->getApplication();

        $Application->get('/', function (Request $request, Response $response, $args) {

            global $Core;
            $Core->setContext($response);

            $Core->Template->setVar('index', 'lol');
            $Core->Template->parseTemplate('Index/Index.twig');

            return $response;
        });

        $Application->redirect('/Index', '/', 301);
    }
}

$CurrentView = new Index_View();
