<?php

use Slim\Psr7\Response;

abstract class AjaxHandlerBase
{
    protected $mResponse;

    function setResponse($pResponse)
    {
        $this->mResponse = $pResponse;
    }

    function setRawResponse($pData, $pReturnCode = 200)
    {
        $lResponse = new Response();

        $lResponse = $lResponse->withHeader('Content-Type', 'text/html');
        $lResponse->getBody()->write($pData);

        $this->setResponse($lResponse);
    }

    function setJsonResponse(array $pData, $pReturnCode = 200)
    {
        $lResponse = new Response($pReturnCode);

        $lResponse = $lResponse->withHeader('Content-Type', 'application/json');
        $lResponse->getBody()->write(json_encode($pData));

        $this->setResponse($lResponse);
    }

    function getResponse()
    {
        return $this->mResponse;
    }
}

?>