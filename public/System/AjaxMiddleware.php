<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AjaxMiddleware
{

    private $mAllowedMethod;

    public function __construct()
    {
        $this->mAllowedMethod = ['POST', 'GET', 'PUT'];
    }

    public function __invoke(Request $request, RequestHandler $handler):Response
    {
        global $Core;

        $lPath = $request->getUri()->getPath();

        print_r($lPath);
    }

    public function RegisterPath()
    {
        global $Core;

        $lConfigFile = dirname(__FILE__).'/../routes.ajax.json';

        if(!file_exists($lConfigFile))
        {
            $Core->Logger->Write("Router", "Unable to locate file routes.ajax.json. abort.");
            return;
        }

        $lConfig = json_decode(file_get_contents($lConfigFile));

        $lApplication = $Core->getApplication();

        foreach($lConfig as $lKey => $lPath)
        {
            if(!in_array($lPath->method, $this->mAllowedMethod))
            {
                $Core->Logger->Write("Router", "Find in allowed ajax method '".$lPath->method."' for ".$lPath->path." abort.");
                continue;
            }

            $lMethod = strtolower($lPath->method);
            $test = 'test';

            $lApplication->{$lMethod}('/ajax'.$lPath->path, function (Request $request, Response $response, $args) use($lPath) {
                
                global $Core;

                $Core->setCurrentRequest($request);

                $lAjaxFile = dirname(__FILE__).'/../templates/ajax/'.$lPath->file;

                if(!file_exists($lAjaxFile))
                {
                    $Core->Logger->Write("Router", "Unable to find ajax file ".$lAjaxFile." abort.");
                    $lResponse = $response->withStatus(404);
                    return $lResponse;
                }
                    
                include $lAjaxFile;

                if(!class_exists('AjaxHandler'))
                {
                    $Core->Logger->Write("Router", "Class AjaxHandler not found in ".$lAjaxFile." abort.");
                    $lResponse = $response->withStatus(500);
                    return $lResponse;
                }


                $lHandler = new AjaxHandler();
                $lHandler->exec();
                
                return $lHandler->getResponse();
            });
        }
    }
}

$Core->push('AjaxMiddleware', new AjaxMiddleware());

?>