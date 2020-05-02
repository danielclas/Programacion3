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

        return JWT::encode(self::$payload,self::$key);
    }

    public static function validarJWT(){
        
        $headers = getallheaders();
        $token = $headers['token'] ?? NULL;
        $decoded;

        try {
            $decoded = JWT::decode($token, self::$key, array('HS256'));
        } catch (Exception $e) {            
            return NULL;
        }
        
        return $decoded['user'];
    }
}

