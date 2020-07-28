<?php

namespace App\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;
use App\Utils\rtaJsend;

class AllCapsMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $existingContent = $response->getBody();
        $data = json_decode($existingContent)->data;

        echo var_dump($data);
        $response->getBody()->write("");
    
        return $response;
    }
}