<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

global $Core;

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/System/Base.php';

$Container = new Container();
$RouteList = array();

// Init Cache Module
$Core->Cache->Init();

$app = AppFactory::create();
AppFactory::setContainer($Container);

$Core->setApplication($app);
$app->add($Core->Middleware);

$app->run();






/*$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});*/
