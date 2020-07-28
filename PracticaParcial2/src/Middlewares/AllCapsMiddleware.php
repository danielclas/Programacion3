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
        $res = $existingContent;
        $success = false;

        if(isset($data)){
            foreach($data as $obj){
                $today = new DateTime(); 
                if($obj->fecha->getTimestamp() > $today->getTimestamp()){
                    foreach($obj as $key => $value){
                        if(ctype_alnum($obj[$key])){
                            $obj[$key] = \strtoupper($obj[$key]);
                        }
                    }
                }
            }
            $res = $data;
            $success = true;
        }        
        
        $response->getBody()->write(ResponseParser::parse($success, $res));
    
        return $response;
    }
}