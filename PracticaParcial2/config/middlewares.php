<?php
// Aca incluyo mis middlewares
use Slim\App;
use App\Middlewares\JsonContentType;

return function(App $app){

    $app->addBodyParsingMiddleware();
    $app->add(new JsonContentType());
};