<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Mascota;

class MascotaController{

    public function registerMascota(Request $request, Response $response, $args){
        
        $rta = "registerMascota!";
        // $rta = json_encode(Usuario::all());
        $response->getBody()->write($rta);
        
        return $response;
    }

    public function getMascotaHistory(Request $request, Response $response, $args){
        
        $rta = "getMascotaHistory";
        // $rta = json_encode(Usuario::all());
        $response->getBody()->write($rta);
        
        return $response;
    }
}