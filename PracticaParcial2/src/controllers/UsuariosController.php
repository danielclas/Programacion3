<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use App\Utils\ResponseParser;
use App\Utils\RequestValidator;
use App\Utils\Authenticator;
use Config\Database;
use \Firebase\JWT\JWT;

class UsuariosController {

    public function registro(Request $request, Response $response, $args){

        //1 es user, 2 es admin
        $params = $request->getParsedBody() ?? null;
        $success = false;
        $data = 'Los datos enviados son incorrectos o el mail ya está registrado';

        if(isset($params) && self::validateRegistro($params)){
            try{
                $user = new Usuario();
                $user->usuario = $params["nombre"];
                $user->email = strtolower($params["email"]);
                $user->tipo = $params["tipo"];
                $user->clave = Authenticator::encryptPassword(strtolower($params["clave"]));
                $user->save();

                $success = true;
                $data = 'Usuario registrado exitosamente';
            }catch(Exception $e){
                $response->getBody()->write(ResponseParser::parse($success, $data));
                return $response;
            }
        }                
        
        $response->getBody()->write(ResponseParser::parse($success, $data));

        return $response;
    }

    public function login(Request $request, Response $response, $args){

        $params = $request->getParsedBody() ?? null;
        $success = false;
        $data = 'El email o clave enviados son incorrectos';

        if(isset($params) && self::validateLogin($params)){
            $temp = Usuario::where('email', $params['email'])->first();

            $success = true;
            $data = Authenticator::createToken($temp, strtolower($params['clave']));
        }

        $response->getBody()->write(ResponseParser::parse($success, $data));
        return $response;        
    }

    private static function validateRegistro($params){

        $valid = true;

        if(!RequestValidator::containsParams($params, ['email', 'clave', 'tipo', 'nombre'])){
            $valid = false;
        }else{
            $valid = !self::emailExists($params['email']);
            if($valid){
                $tipo = $params['tipo'];
                $valid = self::validateTipo($tipo);
                if($valid){
                    $valid = self::validateNombre($params['nombre']);
                }
            }
        }

        return $valid;
    }

    private static function validateLogin($params){

        $valid = true;

        if(!RequestValidator::containsParams($params, ['email', 'clave'])){
            $valid = false;
        }else{
            $valid = self::emailExists($params['email']);
            if($valid){
                $valid = self::passwordExists($params['clave']);
            }

        }
        return $valid;
    }

    private static function emailExists($email){
               
        return Usuario::where('email', strtolower($email))->exists();
    }

    private static function passwordExists($clave){

        $temp = Authenticator::encryptPassword(strtolower($clave));
        return Usuario::where('clave', $temp)->exists();
    }

    private static function validateTipo($tipo){

        return is_numeric($tipo) && $tipo>=1 && $tipo<=2;
    }

    private static function validateNombre($nombre){

        return strlen($nombre)>=4 && strpos($nombre, " ") === false;
    }
}