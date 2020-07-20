<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\RtaJsend;
use \Firebase\JWT\JWT;

class TokenValidatorMiddleware
{    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $existingContent = (string) $response->getBody();
        $response = new Response();
        try{
            $tokenExiste = false;
            $headers = $request->getHeaders();
            foreach ($headers as $key => $value) {
                if($key == 'token'){
                    $tokenExiste = true;
                    break;
                }
            }
            if($tokenExiste){
                $token_recibido = $request->getHeaders()['token'][0];
                if($token_recibido != ''){
                    $usuario_decodificado = JWT::decode($token_recibido, "clavesecreta", array('HS256'));
                    $response->getBody()->write($existingContent);
                } else {
                    $response->getBody()->write(RtaJsend::JsendResponse('error','No se recibió ningun token'));
                }
            } else {
                $response->getBody()->write(RtaJsend::JsendResponse('error','No existe ningun header que se llame token'));
            }
        } catch (\Throwable $th) {
            $response = new Response();
            $response->getBody()->write(RtaJsend::JsendResponse('error','Token JWT erroneo '));
            return $response;
        }
    
        return $response;
    }
}