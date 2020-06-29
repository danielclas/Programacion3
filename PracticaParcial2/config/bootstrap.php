<?php

require_once __DIR__ . "/../vendor/autoload.php";
use Slim\Factory\AppFactory;
use Config\Database;

new Database();
$app = AppFactory::create();
$app->setBasePath('/practicaparcial2/public');

//Registrar rutas
(require_once __DIR__ . '/routes.php')($app);

//Registrar middleware
(require_once __DIR__ . '/middlewares.php')($app);

return $app;