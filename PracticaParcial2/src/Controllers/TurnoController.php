<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Turno;

class TurnoController{

    public function setAppointment(Request $request, Response $response, $args){
        
        $rta = "Set appointment!";
        // $rta = json_encode(Usuario::all());
        $response->getBody()->write($rta);
        
        return $response;
    }

    public function getAppointments(Request $request, Response $response, $args){
        
        $rta = "getAppointments";
        // $rta = json_encode(Usuario::all());
        $response->getBody()->write($rta);
        
        return $response;
    }
}