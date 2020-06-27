<?php

// Logger class

class Logger
{
    private $mBaseLogDir;

    public function __construct()
    {
        $this->mBaseLogDir = dirname(__DIR__).'/Logs/'.date('d-m-Y');

        if(!file_exists($this->mBaseLogDir))
            mkdir($this->mBaseLogDir);

        if(!file_exists($this->mBaseLogDir.'/logs.txt'))
            file_put_contents($this->mBaseLogDir.'/logs.txt', '');
    }

    public function write($pModule, $pStr)
    {
        global $Core;

        if(property_exists($Core->Config, "log") && property_exists($Core->Config->log, "enabled")
            && !$Core->Config->log->enabled)
            return;

        file_put_contents($this->mBaseLogDir.'/logs.txt', '['.$pModule.'] '.$pStr. "\n", FILE_APPEND);
    }
}

$Core->push("Logger", new Logger());