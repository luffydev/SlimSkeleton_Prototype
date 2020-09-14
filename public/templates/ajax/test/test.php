<?php

use Slim\Psr7\Response;
include dirname(__FILE__)."/../AjaxHandler.Base.php";

class AjaxHandler extends AjaxHandlerBase
{
    public function __construct()
    {
    }

    public function exec()
    {
        global $Core;
        

        //var_dump($Core->getCurrentRequest());

        $this->setJsonResponse(array('test' => 1));
        // Your Code Here
    }
}

?>