<?php

require_once __DIR__ ."./vendor/autoload.php"; 
require_once './clases/cliente.php';
require_once './clases/helper.php';
require_once './clases/authenticator.php';
require_once './clases/pizza.php';
require_once './clases/venta.php';

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
            case '/ventas':
                $tipo = $_POST['tipo'] ?? NULL;
                $sabor = $_POST['sabor'] ?? NULL;

                if(isset($tipo) && isset($sabor)){
                    $usuario = authenticator::validarJWT();
                    if(isset($usuario) && $usuario->tipo=='cliente'){
                        if(pizza::hayStock($tipo,$sabor)){
                            $precio = pizza::actualizarStock($tipo,$sabor);
                            $venta = new venta($usuario->email,$tipo,$sabor,$precio);
                            $venta->registrarVenta();
                            $message = "Venta registrada exitosamente";
                            $success = true;
                        }else{
                            $message = "No hay stock suficiente para la venta";
                        }
                    }else{
                        $message = "Cliente no existe o no es tipo usuario";
                    }
                }else{
                    $message = "Datos invalidos";
                }
            break;
            default:
                $message = "Ruta invalida";
        }
    }else if($request_method == 'GET'){
        switch($path_info){
            case '/pizzas':
                $usuario = authenticator::validarJWT();
                if(isset($usuario)){
                    $esEncargado = $usuario->tipo=='encargado';
                    $message = pizza::mostrarPizzas($esEncargado);
                    $success = true;
                }
                $message = $success ? $message : 'Error obteniendo el listado';
            break;
            case '/ventas':
                $usuario = authenticator::validarJWT();
                if(isset($usuario)){
                    $esEncargado = $usuario->tipo=='encargado';
                    $message = venta::obtenerVentas($esEncargado);
                    $success = true;
                }else{
                    $message = "Usuario invalido";
                }
            break;
            /**
             * 6. (GET) ventas: Si es encargado muestra el monto total y la cantidad de las ventas, si es cliente solo las
             * compras de dicho usuario.
             * 
             */
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
