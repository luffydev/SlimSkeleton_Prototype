<?php

global $Core;
use Slim\Views\Twig;

class Template
{
    private $mTwigPtr = null;
    private $mVars = [];

    public function __construct()
    {
        global $Core;

        $lCacheDir = false;

        if($Core->Config->template->cache)
            $lCacheDir = __DIR__.'/../'.$Core->Config->template->cache_dir;

        $lTemplateDir = __DIR__.'/../'.$Core->Config->template->dir;

        $this->mTwigPtr = Twig::create($lTemplateDir,
                                       ['cache' => $lCacheDir]);
    }

    public function setVar($pName, $pValue)
    {
        $this->mVars[$pName] = $pValue;
    }

    public function parseTemplate($pTemplate)
    {
        global $Core;
        return $this->mTwigPtr->render($Core->getContext(), $pTemplate, $this->mVars);
    }
}

$Core->push('Template', new Template());

