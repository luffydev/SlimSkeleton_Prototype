<?php

class Model
{
    public function __construct()
    {
    }

    public function load($pName)
    {

        $lModelPath = __DIR__.'/../Model/'.$pName.'.php';

        if(!file_exists($lModelPath))
            return null;

        include($lModelPath);

        if(!class_exists($pName.'_Model'))
            return null;

        $lClass = $pName.'_Model';    
        $lPtr = new $lClass();

        return $lPtr;
    }
}

$Core->push('Model', new Model());

?>