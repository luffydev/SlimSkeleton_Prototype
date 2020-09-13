<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include dirname(__DIR__).'/View/View.Base.php';

class Blog_View extends View_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        global $Core;
        $Application = $Core->getApplication();  
        
        $Application->get($this->getConfig()->url, function (Request $request, Response $response, $args) {

            global $Core;
            $Core->setContext($response);
            
            $blog = $Core->Model->load('Blog');
            $latest = $blog->ListLatestArticles('100');
            $Core->Template->setVar('latest', $latest);
            $Core->Template->parseTemplate('Blog/Index.twig');

            return $response;
        });
    }
    public function article()
    {
        global $Core;
        $Application = $Core->getApplication();  
        
        $Application->get($this->getConfig()->url, function (Request $request, Response $response, $args) {

            global $Core;
            $Core->setContext($response);
            $id_article = $args['id'];
            $blog = $Core->Model->load('Blog');
            $article = $blog->GetArticleFromId($id_article);
            $relatives = $blog->ListRelativesArticles('6',$id_article);
         
            $Core->Template->setVar('article', $article);
            $Core->Template->setVar('relatives', $relatives);
            $Core->Template->setVar('index', 'lol');
            $Core->Template->parseTemplate('Article/Index.twig');

            return $response;
        });
    }
    
}

$CurrentView = new Blog_View();
