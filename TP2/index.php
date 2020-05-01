<?php

require_once __DIR__ ."./vendor/autoload.php"; 
require_once './clases/usuario.php';
require_once './clases/helper.php';
require_once './clases/authenticator.php';

$path_info = $_SERVER['PATH_INFO'] ?? NULL;
$request_method = $_SERVER['REQUEST_METHOD'] ?? NULL;
$message = '';
$success = false;

if(isset($path_info) && isset($request_method)){
    if($request_method == "POST"){
        switch($path_info){
            case "/signin":
                $isValid = usuario::isValidUser($_POST);
                if($isValid && empty(usuario::return_user($_POST['email']))){
                    $usuario = new usuario($_POST);
                    usuario::writeToFile($usuario);
                    $message="Usuario registrado exitosamente";
                    $success=true;
                }else{
                    $message = "Usuario invalido o ya existe";
                }               

                echo helper::formatResponse($message,$success);
            break;
            case "/login":
                $emailLogin = $_POST['email'] ?? NULL;
                $claveLogin = $_POST['clave'] ?? NULL;
                $user = usuario::return_user($emailLogin);

                if(isset($user) && $claveLogin == $user['clave']){
                    $message = authenticator::generateJWT($user);
                    $success = true;
                }else{
                    $message = "Credenciales invalidas";
                }                
            break;
        }
    }else if($request_method == "GET"){
        switch($path_info){
            case "/detalle":
                $token = $_GET['token'] ?? NULL;
                $message = authenticator::validateJWT($token);

                if(isset($message)){
                    $success = true;
                }else{
                    $message = "JWT Invalido";
                }

            break;
            case "/lista":
                $token = $_GET['token'] ?? NULL;
                $usuario = authenticator::validateJWT($token);

                if(isset($usuario) && $usuario->tipo=='admin'){
                    $success = true;
                    $message = usuario::readFromFile();
                }
            break;
        }
    }else{
        $message = "Method not supported";
    }
}else{
    $message = "Invalid request";
}

echo helper::formatResponse($message,$success);
/*
1- POST signin: recibe email, clave, nombre, apellido, telefono y tipo (user, admin) y lo guarda en un archivo.
2- POST login: recibe email y clave y chequea que existan, 
si es así retorna un JWT de lo contrario informa el error (si el email o la clave están equivocados) .
A PARTIR DE AQUI TODAS LAS RUTAS SON AUTENTICADAS.
3- GET detalle: Muestra todos los datos del usuario actual.
4- GET lista: Si el usuario es admin muestra todos los usuarios, si es user solo los del tipo user.
*/