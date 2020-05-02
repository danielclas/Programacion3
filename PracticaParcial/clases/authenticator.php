<?php

require_once './clases/cliente.php';
use \Firebase\JWT\JWT;

class authenticator{

    private static $key = "miclave";
    private static $payload;

    public static function generarJWT($cliente){
        
        authenticator::$payload=array(
            'user'=>$cliente,
            'time'=>time()
        );

        return JWT::encode(authenticator::$payload,authenticator::$key);
    }

    public static function validarJWT($token){

        $decoded;

        try {
            $decoded = JWT::decode($token, authenticator::$key, array('HS256'));
        } catch (Exception $e) {            
            return NULL;
        }
        
        return $decoded;
    }
}

