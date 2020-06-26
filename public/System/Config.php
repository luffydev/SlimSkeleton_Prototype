<?php

global $Core;

class Config extends stdClass
{
    public function __construct()
    {
        if(file_exists(__DIR__.'/../config.json'))
        {
            $lConfig = json_decode(file_get_contents(__DIR__.'/../config.json'));

            foreach($lConfig as $lKey => $lItem)
                $this->$lKey = $lItem;

        }else
            die('Unable to find application config');
    }
}

$Core->push('Config', new Config());
