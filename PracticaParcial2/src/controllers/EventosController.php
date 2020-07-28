<?php

namespace App\Controllers;

use Config\Database;
use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\ResponseParser;
use App\Utils\Authenticator;
use App\Utils\RequestValidator;
use App\Models\Evento;
use App\Models\Mascota;
use App\Models\Usuario;
use DateTime;

class EventosController {


    public function registrarEvento(Request $request, Response $response, $args){  
        
        $params = $request->getParsedBody() ?? null;
        $token = $request->getheaders()['token'][0] ?? null;
        $success = false;
        $data = 'Hay un error en los datos enviados';
        
        if(isset($params) && RequestValidator::containsParams($params, ['descripcion','fecha']) && isset($token)){
            try{
                $user = Authenticator::decryptToken($token);

                //Debe ser tipo user
                if(isset($user) && isset($user->tipo) && $user->tipo == '1'){
                    if(self::isValidFecha($params['fecha'])){
                        $evento = new Evento();
                        $evento->descripcion = $params['descripcion'];
                        $evento->fecha = new DateTime($params['fecha']);
                        $evento->id_usuario = $user->id;  

                        $success = $evento->save();
                        $data = $success ? 'Evento registrado exitosamente' : $data;
                    }else{
                        $data = 'La fecha ingresada no es valida';
                    }
                }else{
                    $data = 'El usuario logeado es invalido o no es del tipo user';
                }
            }catch(Exception $e){
                $response->getBody()->write(ResponseParser::parse($success, $data));
                return $response;
            }
        }

        $response->getBody()->write(ResponseParser::parse($success, $data));
        return $response;
    }
    
    public function obtenerEventos(Request $request, Response $response, $args){

        $token = $request->getheaders()['token'][0] ?? null;
        $success = false;
        $data = 'Hay un error en los datos enviados';

        if(isset($token)){
            try{
                $user = Authenticator::decryptToken($token);
                if(isset($user) && isset($user->tipo)){
                    if($user->tipo == '1'){
                        $queryResult = Evento::where('id_usuario', $user->id)->orderBy('fecha', 'desc')->get();

                        $success = isset($queryResult);
                        $data = $success ? $queryResult : 'No pudo obtenerse el dato deseado';
                    }else if($user->tipo == '2'){
                        $success = true;
                        $data = "Tipo admin";
                    }else{
                        $data = 'Error en el tipo de usuario';
                    }
                }else{
                    $data = 'Hubo un problema obteniendo el usuario del token';
                }
            }catch(Exception $e){
                $response->getBody()->write(ResponseParser::parse($success, $data));
                return $response;
            }
        }

        $response->getBody()->write(ResponseParser::parse($success, $data));
        return $response;
    }

    public function modificarEvento(Request $request, Response $response, $args){  
        
        $params = $request->getParsedBody() ?? null;
        $token = $request->getheaders()['token'][0] ?? null;
        $success = false;
        $data = 'Hay un error en los datos enviados';
        
        var_dump(isset($args['ids']));
        if(isset($params) && isset($args['id']) && RequestValidator::containsParams($params, ['fecha']) && isset($token)){
            try{
                $user = Authenticator::decryptToken($token);
                $idEvento = $args['id'];
                if(isset($user) && isset($user->tipo) && $user->tipo == '1'){
                    if(self::isValidFecha($params['fecha'])){
                        $fecha = new DateTime($params['fecha']);
                        if(Evento::where('id', $idEvento)->exists()){
                            $updateResult = Evento::where('id', $idEvento)->update(['fecha' => $fecha]);

                            $success = $updateResult == 1;
                            $data = $success ? 'Registro actualizado exitosamente': $data;
                        }else{
                            $data = 'No existe vento con el id indicado';
                        }
                    }else{
                        $data = 'La fecha ingresada no es valida';
                    }
                }else{
                    $data = 'El usuario del token no es de tipo user';
                }
                
            }catch(Exception $e){
                $response->getBody()->write(ResponseParser::parse($success, $data));
                return $response;
            }
        }

        $response->getBody()->write(ResponseParser::parse($success, $data));
        return $response;
    }

    private static function isValidFecha($date){

        $valid = true;

        try{
            $temp = new DateTime($date);
            $valid = isset($temp);
        }catch(Exception $e){
            return false;
        }

        return $valid;
    }
}