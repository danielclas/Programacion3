<?php

namespace App\Controllers;

use Config\Database;
use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\ResponseParser;
use App\Utils\Authenticator;
use App\Utils\RequestValidator;
use App\Models\Turno;
use App\Models\Mascota;
use App\Models\Usuario;
use DateTime;

class TurnosController {


    public function getTurnos(Request $request, Response $response, $args){        

        date_default_timezone_set("America/Buenos_Aires");
        $openHour = new DateTime('09:00');
        $closeHour = new DateTime('17:00');
        $today = new DateTime(); 

        $params = $args ?? null;
        $token = $request->getheaders()['token'][0] ?? null;
        $success = false;
        $data = 'Hay un error en los datos enviados';

        if(isset($params) && isset($params['id_usuario']) && isset($token)){
            try{
                $user = Authenticator::decryptToken($token);

                if(isset($user) && $user->tipo == '2'){ //2 es veterinario
                    $queryResult = Turno::select('fecha', 'mascotas.nombre', 'mascotas.fecha_nacimiento', 'usuarios.usuario as dueño')
                    ->whereDate('fecha', $today->format('Y-m-d'))
                    ->where('veterinario_id', $params['id_usuario'])
                    ->join('mascotas', 'turnos.mascota_id', '=', 'mascotas.id')
                    ->join('usuarios', 'mascotas.cliente_id', '=', 'usuarios.id')
                    ->get();

                    $success = true;
                    $data = $queryResult;                  
                }else if(isset($user) && $user->tipo == '3'){ //3 es cliente
                    /*
                        Los clientes deben poder ver la fecha del turno, el nombre de la mascota y el
                        nombre del veterinario, de todos los turnos de todas sus mascotas.
                    */
                    $queryResult = [];

                    $success = true;
                    $data = $queryResult;   
                }else{
                    $data = 'El usuario del request no es valido';
                }
            }catch(Exception $e){
                $response->getBody()->write(ResponseParser::parse($success, $data));
                return $response;
            }
        }

        $response->getBody()->write(ResponseParser::parse($success, $data));
        return $response;
    }
    
    public function getTurnosMascota(Request $request, Response $response, $args){

    }

    public function cargarTurno(Request $request, Response $response, $args){    
        
        date_default_timezone_set("America/Buenos_Aires");

        $params = $request->getParsedBody() ?? null;
        $temp = ['fecha', 'mascota_id', 'veterinario_id'];
        $token = $request->getheaders()['token'][0] ?? null;
        $success = false;
        $data = 'Hay un error en los datos enviados';

        if(isset($params) && isset($token) && RequestValidator::containsParams($params, $temp)){
            try{
                $user = Authenticator::decryptToken($token);
                $exists = Mascota::where('id', $params['mascota_id'])->exists();
                $veterinario = Usuario::where('id', $params['veterinario_id'])->first();

                if($user->tipo == '3' && $exists && isset($veterinario) && $veterinario->tipo == '2'){
                    $clientDate = new DateTime($params["fecha"]);
                    $clientHour = new DateTime($clientDate->format('H:i'));  

                    if(self::isValidFechaTurno($clientDate, $clientHour)){
                        $query = [['fecha', '=', $clientDate], ['veterinario_id', '=', $veterinario->id]];
                        if(!Turno::where($query)->exists()){
                            $turno = new Turno();
                            $turno->veterinario_id = $veterinario->id;
                            $turno->mascota_id = $params['mascota_id'];
                            $turno->fecha = $clientDate;

                            $turno->save();

                            $success = true;
                            $data = 'Turno registrado exitosamente';
                        }else{
                            $data = 'Ya existe un turno para esa fecha y hora';
                        }
                    }else{
                        $data = 'La fecha ingresada no es valida. Los turnos son cada 30 minutos exactos';
                    }
                }else{
                    $data = 'El usuario logeado no es tipo cliente o algunos datos son invalidos';
                }
            }catch(Exception $e){
                $response->getBody()->write(ResponseParser::parse($success, $data));
                return $response;
            }
        }

        $response->getBody()->write(ResponseParser::parse($success, $data));
        return $response;
    }

    public static function isValidFechaTurno($clientHour, $clientDate){

        $openHour = new DateTime('09:00');
        $closeHour = new DateTime('17:00');
        $today = new DateTime(); 

        $valid = $clientHour->getTimestamp() >= $openHour->getTimestamp()  && $clientHour->getTimestamp() <= $closeHour->getTimestamp() && $today->getTimestamp() <= $clientDate->getTimestamp();

        return $valid && ($clientHour->format('i') == "30" || $clientHour->format('i') == "00");
    }
}