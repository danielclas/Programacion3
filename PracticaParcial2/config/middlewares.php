<?php

use Slim\App;
use App\Middleware\AllCapsMiddleware;
use App\Middleware\IsAuthenticatedMiddleware;

return function(App $app){
    $app->addBodyParsingMiddleware();

    $app->add(new AllCapsMiddleware());
    $app->add(new IsAuthenticatedMiddleware());
};