<?php

global $Core;

use Twig\Environment;
use Twig\TwigFunction;
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


        $function = new TwigFunction('breadcrumb', function ($separator = ' &raquo; ', $home = 'Home') {

            $lParsed = explode('/',parse_url($_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'], PHP_URL_PATH));

            $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
                                        
            // This will build our "base URL" ... Also accounts for HTTPS :)
            $base = (array_key_exists('HTTPS', $_SERVER) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/'.$lParsed[1];
                                        
            // Initialize a temporary array with our breadcrumbs. (starting with our home page, which I'm assuming will be the base URL)
            $breadcrumbs = array("<a href=\"$base\">".$home."</a>");
                                        
            // Find out the index for the last value in our path array
            $lKeys = array_keys($path);
            $last = end($lKeys);
                                        
            // Build the rest of the breadcrumbs
            foreach ($path AS $x => $crumb) {
                // Our "title" is the text that will be displayed (strip out .php and turn '_' into a space)
                $title = ucwords(str_replace(Array('.php', '_'), Array('', ' '), $crumb));
                                        
                // If we are not on the last index, then display an <a> tag
                if ($last && @$x != @$last)
                    $breadcrumbs[] = "<a href=\"".$base.$crumb."\">".$title."</a>";
                    // Otherwise, just display the title (minus)
                else
                    $breadcrumbs[] = $title;
                }
                                        
                // Build our temporary array (pieces of bread) into one big string :)
                return implode($separator, $breadcrumbs);
        });                                       

        $this->mTwigPtr->getEnvironment()->addFunction($function);
                                            
    }

    public function buildBreadcrumb()
    {

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

