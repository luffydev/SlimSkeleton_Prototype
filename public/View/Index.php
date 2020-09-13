<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include dirname(__DIR__).'/View/View.Base.php';

class Index_View extends View_Base
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

    public function numero()
    {
        global $Core;
        $Application = $Core->getApplication();  
        
        $Application->get($this->getConfig()->url, function (Request $request, Response $response, $args) {

            global $Core;
            $Core->setContext($response);

            $Core->Template->setVar('index', 'lol');
            $Core->Template->parseTemplate('Index/Index.twig');

            return $response;
        });
    }
}

$CurrentView = new Index_View();
