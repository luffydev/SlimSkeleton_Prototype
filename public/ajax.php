<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Symfony\Component\Translation\Translator;


global $Core;

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/System/Base.php';

$Container = new Container();

// Init Database Module
$Core->Database->Init();

// Init Cache Module
$Core->Cache->Init();

$app = AppFactory::create();
AppFactory::setContainer($Container);

$Core->setApplication($app);

$Core->AjaxMiddleware->RegisterPath();

$app->run();

