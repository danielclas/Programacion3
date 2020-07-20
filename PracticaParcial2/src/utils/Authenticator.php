<?php

namespace App\Utils;
use \Firebase\JWT\JWT;

class Authenticator{
    
    static public function encryptPassword($password){
        
        return JWT::encode(array($password), "clavesecreta");        
    }

    static public function createJWT($user, $password){

        $payload = array(
            'id' => $user->id,
            'email' => $user->email,
            'tipo' => $user->tipo_id,
            'password' => $password,
        );

        $jwt = array("token" => JWT::encode($payload, "clavesecreta"));
        
        return RtaJsend::JsendResponse("success", $jwt);
    }
}