<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Config\Database;
new Database();

use Slim\Factory\AppFactory;
$app = AppFactory::create();
$app->setBasePath('/programacion3/practicaparcial2/public');

use Psr\Http\Message\ServerRequestInterface;
$app->addRoutingMiddleware();

(require_once __DIR__ . './routes.php')($app);
(require_once __DIR__ . './middlewares.php')($app);

$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $payload = ['error' => $exception->getMessage()];

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );
    
    return $response;
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

return $app;