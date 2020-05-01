<?php

require_once './clases/usuario.php';
use \Firebase\JWT\JWT;

class authenticator{

    private static $key = "miclave";
    private static $payload;

    public static function generateJWT($user){
        
        authenticator::$payload=$user;

        return JWT::encode(authenticator::$payload,authenticator::$key);
    }

    public static function validateJWT($token){

        $decoded;

        try {
            $decoded = JWT::decode($token, authenticator::$key, array('HS256'));
        } catch (Exception $e) {            
            return NULL;
        }
        
        return $decoded;
    }
}

