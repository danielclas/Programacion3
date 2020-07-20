<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use App\Utils\RtaJsend;
use App\Utils\ValidarPost;
use Config\Database;
use \Firebase\JWT\JWT;

class UsuariosController {

    public function registro(Request $request, Response $response, $args){
        $usuario = new Usuario();

        $datosARegistrar = $request->getParsedBody() ?? [];

        if(empty($datosARegistrar)){
            $rta = RtaJsend::JsendResponse('Registro Usuario ERROR','No se recibieron datos para registrar');
        } else {
            $usuario = ValidarPost::RegistroUsuario($usuario, $datosARegistrar);
            $rta = RtaJsend::JsendResponse('Registro Usuario',(($usuario->save()) ? 'ok' : 'error'));
        }
        $response->getBody()->write($rta);
        return $response;
    }

    public function login(Request $request, Response $response, $args){

        $datosAValidar = $request->getParsedBody() ?? [];
        
        if(empty($datosAValidar)){  
            $rta = RtaJsend::JsendResponse('LOGIN Usuario ERROR','No se recibieron datos para loguear');
        } else {
            $email_recibido = $datosAValidar['email'];
            $password_recibido = $datosAValidar['clave'];
            if(($email_recibido != '') && $password_recibido != '') {
                $usuario_leidoSQL = Usuario::all()->where('email',$email_recibido)->first();
                $rta = ValidarPost::LoginUsuario($usuario_leidoSQL, $password_recibido);
            }
        }
        $response->getBody()->write($rta);
        return $response;
    }
}