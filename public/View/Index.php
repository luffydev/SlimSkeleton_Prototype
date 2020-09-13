<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include dirname(__DIR__).'/View/View.Base.php';

class Index_View extends View_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        global $Core;
        $Application = $Core->getApplication();

        $Application->get('/', function (Request $request, Response $response, $args) {

            global $Core;
            $Core->setContext($response);

            $blog = $Core->Model->load('Blog');
            
            $latest = $blog->ListLatestArticles('6');
          
            $Core->Template->setVar('latest', $latest);
            $Core->Template->parseTemplate('Index/Index.twig');

            return $response;
        });

        $Application->redirect('/Index', '/', 301);
    }
    public function html()
    {
        global $Core;
        $Application = $Core->getApplication();  
        
        $Application->get($this->getConfig()->url, function (Request $request, Response $response, $args) {
           
            global $Core;
            $Core->setContext($response);
            $Core->Template->setVar('index', 'lol');
            $name = ucfirst($this->getConfig()->name);
            
            //echo $Core->VarBuilder->BuildElement($this->getConfig()->var, 'test', '', ['titre' => 'test', 'var' => 'variable_1']);
           
            //echo $Core->VarBuilder->BuildElement($this->getConfig()->var, 'test_2', 'test_titre', ['titre' => 'test', 'var' => 'variable_1']);

            $Core->Template->setVar('meta', [
                'title' => $this->getConfig()->title ?? '',
                'description' => $this->getConfig()->description ?? ''
                ]
            );
            $Core->Template->parseTemplate("$name/Index.twig");

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
            $comment = $Core->Model->load('Comment');
            $Core->Template->setVar('zozo', $comment->GetCommentFromId('62'));
            $Core->Template->setVar('index', 'lol');
            $Core->Template->parseTemplate('Article/Index.twig');

            return $response;
        });
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
