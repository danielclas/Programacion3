<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;

class UsuarioController{

    public function signUp(Request $request, Response $response, $args){
        
        $rta = "Sign up!";
        // $rta = json_encode(Usuario::all());
        $response->getBody()->write($rta);
        
        return $response;
    }

    public function logIn(Request $request, Response $response, $args){
        
        $rta = "Log in!";
        // $rta = json_encode(Usuario::all());
        $response->getBody()->write($rta);
        
        return $response;
    }
}