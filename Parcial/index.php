<?php

require_once __DIR__ ."./vendor/autoload.php"; 
require_once './clases/cliente.php';
require_once './clases/helper.php';
require_once './clases/authenticator.php';
require_once './clases/pizza.php';

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
                    $success = helper::guardarEnArchivo('./archivos/datos.json',$cliente);
                }
                $message = $success ? "Cliente registrado exitosamente" : "Error registrando el cliente";
            break;
            case '/login':
                $email = $_POST['email'] ?? NULL;
                $clave = $_POST['clave'] ?? NULL;
                if(isset($email) && isset($clave)){
                    //Devuelve el cliente si existe, o NULL en su defecto
                    $cliente = cliente::devolverCliente('email',$email);
                    if(isset($cliente)){
                        $message = authenticator::generarJWT($cliente);
                        $success = true;
                    }else{
                        $message = "Cliente no existe";
                    }
                }else{
                    $message = "Nombre o clave invalidos";
                }
            break;
            case '/pizzas':
                $usuario = authenticator::validarJWT();
                if(isset($usuario) && $usuario->tipo=='encargado' && pizza::esPizzaValida($_POST)){
                    $pizza = new pizza($_POST);
                    $success = helper::guardarEnArchivo('./archivos/pizzas.json', $pizza);
                }
                $message = $success ? "Producto registrado exitosamente" : "Error registrando el producto";
            break;
            default:
                $message = "Ruta invalida";
        }
    }else if($request_method == 'GET'){
        switch($path_info){
            case '/pizzas':
                $message = helper::leerArchivo('./archivos/productos.json');
                $success = true;
            break;
            case '/ventas':
                $usuario = authenticator::validarJWT();
                if(isset($usuario)){
                    $message = venta::obtenerVentas($usuario);
                    $success = true;
                }else{
                    $message = "Usuario invalido";
                }
            break;
            case '/ventas':
                $usuario = authenticator::validarJWT();
                if(isset($usuario)){
                    $message = venta::obtenerVentas($usuario);
                    $success = true;
                }else{
                    $message = "Usuario invalido";
                }
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
