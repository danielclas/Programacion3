<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class JsonContentType
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        return $handler->handle($request)->withHeader('Content-type','application/json');
    }
}