<?php

use DI\Container;
use Slim\Factory\AppFactory;

global $Core;

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/System/Base.php';

$Container = new Container();
$RouteList = array();

// Init Database Module
$Core->Database->Init();

// Init Cache Module
$Core->Cache->Init();

/*$test = $Core->Model->load('BadWord');
$lGB = $test->GibberishTest("Bonjour je m'appel jérémy comment ça va ?");*/


//print_r($test->parseText("Salut connard connard pd"));

$app = AppFactory::create();
AppFactory::setContainer($Container);

$Core->setApplication($app);
$app->add($Core->Middleware);

$app->run();

/*$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});*/



