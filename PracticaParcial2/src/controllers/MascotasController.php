<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\ResponseParser;
use App\Utils\Authenticator;
use App\Utils\RequestValidator;
use App\Models\TipoMascota;
use App\Models\Usuario;
use App\Models\Mascota;

class MascotasController {

    public function cargarTipo(Request $request, Response $response, $args){

        $params = $request->getParsedBody() ?? null;
        $token = $request->getheaders()['token'][0] ?? null;
        $success = false;
        $data = 'Hay un error en los datos enviados o el tipo ya existe';

        if(self::checkUserType($token, '1') && isset($params['tipo']) && !empty($params['tipo'])){
            try{
                if(!TipoMascota::where('tipo', $params['tipo'])->exists()){
                    $tipo_mascota = new TipoMascota();
                    $tipo_mascota->tipo = $params['tipo'];
                    $tipo_mascota->save();
    
                    $success = true;
                    $data = 'Tipo registrado exitosamente';
                }
            }catch(Exception $e){
                $response->getBody()->write(ResponseParser::parse($success, $data));
                return $response;
            }
        }

        $response->getBody()->write(ResponseParser::parse($success, $data));
        return $response;
    }

    public function cargarMascota(Request $request, Response $response, $args){

        $params = $request->getParsedBody() ?? null;
        $arr = ['nombre', 'fecha_nacimiento', 'tipo_mascota_id', 'cliente_id'];
        $token = $request->getheaders()['token'][0] ?? null;
        $success = false;
        $data = 'Hay un error en los datos enviados o el usuario es de tipo incorrecto';
        
        if(isset($params) && isset($token) && RequestValidator::containsParams($params, $arr) && self::checkUserType($token, '3')){
            try{
                $tipoMascotaId = $params['tipo_mascota_id'];
                $clienteId = $params['cliente_id'];

                if(TipoMascota::where('id', $tipoMascotaId)->exists() && Usuario::where('id', $clienteId)->exists()){
                    $mascota = new Mascota();
                    $mascota->nombre = $params['nombre'];
                    $mascota->fecha_nacimiento = $params['fecha_nacimiento'];
                    $mascota->cliente_id = $clienteId;
                    $mascota->tipo_mascota_id = $tipoMascotaId;
                    
                    $mascota->save();

                    $success = true;
                    $data = 'Mascota guardada exitosamente';
                }
            }catch(Exception $e){
                $response->getBody()->write(ResponseParser::parse($success, $data));
                return $response;
            }
        }       

        $response->getBody()->write(ResponseParser::parse($success, $data));
        return $response;
    }

    public static function checkUserType($token, $type){

        $valid = false;

        if(isset($token)){
            $user = Authenticator::decryptToken($token);
            $valid = isset($user->tipo) && $user->tipo == $type;    
        }

        return $valid;
    }


    // public function registrarMascota(Request $request, Response $response, $args){
    //     $mascota = new Mascota();

    //     $mascota->nombre = $_POST['nombre'];
    //     $mascota->edad = $_POST['edad'];
    //     $mascota->cliente_id = $_POST['id_cliente'];

    //     $rta = ResponseParser::JsendResponse('Registro Mascota',($mascota->save()) ? 'Ok' : 'Fallo');
    //     $response->getBody()->write($rta);
    //     return $response;
    // }

    // public function verHistorialMascota(Request $request, Response $response, $args){
    //     $mascota = new Mascota();

    //     $zone=3600 * -3;
    //     $fechaActual = gmdate('d/m/Y', time() + $zone);

    //     $historialTurnosSQL = Mascota::select('mascotas.nombre','mascotas.edad','turnos.fecha')
    //     ->join('turnos','turnos.mascota_id','mascotas.id')
    //     ->where('turnos.fecha','<=',$fechaActual)
    //     ->get();

    //     $rta = ResponseParser::JsendResponse('success',array('Historial de turnos previos al dia de hoy'=>$historialTurnosSQL));
    //     $response->getBody()->write($rta);
    //     return $response;
    // }
}