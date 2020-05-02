<?php

require_once __DIR__ ."./vendor/autoload.php"; 
require_once './clases/cliente.php';
require_once './clases/helper.php';
require_once './clases/authenticator.php';

$path_info = $_SERVER['PATH_INFO'] ?? NULL;
$request_method = $_SERVER['REQUEST_METHOD'] ?? NULL;
$message = '';
$success = false;

if(isset($request_method) && isset($path_info)){
    if($request_method == 'POST'){
        switch($path_info){
            case '/usuario':
                if(cliente::esClienteValido($_POST)){
                    $cliente = new cliente($_POST);                    
                    $success = cliente::guardarEnArchivo($cliente);
                }
                $message = $success ? "Cliente registrado exitosamente" : "Error registrando el cliente";
            break;
            case '/login':
                $nombre = $_POST['nombre'] ?? NULL;
                $clave = $_POST['clave'] ?? NULL;

                if(isset($nombre) && isset($clave)){
                    $cliente = cliente::devolverCliente('nombre',$nombre);
                    if(isset($cliente)){
                        $token = authenticator::generarJWT($cliente);
                        $message = $token;
                    }
                }
                // (POST) login: Recibe nombre y clave y si son correctos devuelve un JWT, de lo contrario informar lo
                // sucedido.
            break;
            case '/stock':

            break;
            case '/ventas':

            break;
            default:
                $message = "Ruta invalida";
        }
    }else if($request_method == 'GET'){
        switch($path_info){
            case '/stock':

            break;
            case '/ventas':

            break;
            default:
                $message = "Ruta invalida";
        }

    }else{
        $message = "Metodo no permitido";
    }
}else{
    $message = "Peticion invalida";
}

echo helper::formatResponse($message,$success);
