<?php

namespace App\Utils;
use \Firebase\JWT\JWT;

class Authenticator{
    
    static $key = 'clavesecreta';

    static public function encryptPassword($password){
        
        return JWT::encode(array($password), self::$key);        
    }

    static public function createToken($user, $password){

        $payload = array(
            'usuario' => $user->usuario,
            'email' => $user->email,
            'tipo' => $user->tipo,
            'password' => $user->clave,
            'id' => $user->id
        );
        
        return ["token" => JWT::encode($payload, self::$key)];      
    }

    static public function decryptToken($token){

        $payload = null;

        try{
            $payload = JWT::decode($token, self::$key, array('HS256'));
        }catch(Exception $e){
            return null;
        }

        return $payload;
    }
}