<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\Authenticator;
use App\Utils\ResponseParser;
use \Firebase\JWT\JWT;

class TokenValidateMiddleware
{    
    public function __invoke(Request $request, RequestHandler $handler): Response{   

        $response = $handler->handle($request);
        $existingContent = (string) $response->getBody();
        $err = ResponseParser::parse(false, 'No tiene permisos para realizar la operación');

        $response = new Response();

        try {
            $token = $request->getheaders()['token'][0] ?? null;
            $user = null;

            if(isset($token)) $user = Authenticator::decryptToken($token);

            $response->getBody()->write(isset($user) ? $existingContent : $err);
        } catch (Exception $e) {
            $response->getBody()->write($err);
        }

        return $response;        
    }
}