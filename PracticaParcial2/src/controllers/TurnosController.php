<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\ResponseParser;
use App\Utils\Authenticator;
use Config\Database;
use \Firebase\JWT\JWT;
use App\Models\Turno;
use App\Models\Mascota;
use App\Models\Usuario;

class TurnosController {

    /*
    Los veterinarios deben poder ver los turnos que tienen asignados en el día con
    el nombre de la mascota, la hora, fecha, el nombre del dueño y la edad de la mascota.
    */
    public function getTurnosVeterinarios(Request $request, Response $response, $args){        

        $params = $args ?? null;
        $token = $request->getheaders()['token'][0] ?? null;
        $success = false;
        $data = 'Hay un error en los datos enviados';

        if(isset($params) && isset($params['id_usuario']) && isset($token)){
            try{
                $user = Authenticator::decryptToken($token);

                if(isset($user) && isset($user->tipo) && user->tipo == '2'){
                    
                }

            }catch(Exception $e){
                $response->getBody()->write(ResponseParser::parse($success, $data));
                return $response;
            }
        }

        $response->getBody()->write(ResponseParser::parse($success, $data));
        return $response;
    }
}