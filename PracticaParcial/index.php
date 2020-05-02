<?php

require_once __DIR__ ."./vendor/autoload.php"; 
require_once './clases/cliente.php';
require_once './clases/helper.php';
require_once './clases/authenticator.php';
require_once './clases/producto.php';
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
                $nombre = $_POST['nombre'] ?? NULL;
                $clave = $_POST['clave'] ?? NULL;

                if(isset($nombre) && isset($clave)){
                    $cliente = cliente::devolverCliente('nombre',$nombre);
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
            case '/stock':
                $usuario = authenticator::validarJWT();

                if(isset($usuario) && $usuario->tipo=='admin' && producto::esProductoValido($_POST)){
                    $producto = new producto($_POST);
                    $success = helper::guardarEnArchivo('./archivos/productos.json', $producto);
                }

                $message = $success ? "Producto registrado exitosamente" : "Error registrando el producto";
            break;
            case '/ventas':
                /*                
                    5. (POST) Ventas:(Solo usuarios) Recibe id y cantidad de producto y usuario y si existe esa cantidad de
                    producto devuelve el monto total de la operación. Si se realiza la venta restar el stock al producto y
                    guardar la venta serializado en el archivo ventas.xxx.
                */
                $idProducto = $_POST['id_producto'] ?? NULL;
                $cantidadProducto = $_POST['cantidad'] ?? NULL;
                $cliente = $_POST['usuario'] ?? NULL;

                if(isset($idProducto) && isset($cantidadProducto) && isset($usuario)){
                    $cliente = cliente::devolverCliente('id',$usuario);
                    if(isset($cliente) && $cliente->tipo=='user'){
                        if(producto::restarStock($idProducto,$cantidadProducto)){

                            $venta = new venta($idProducto,$cantidadProducto,$cliente->id);
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
            case '/stock':
                $message = helper::leerArchivo('./archivos/productos.json');
                $success = true;
            break;
            case '/ventas':
                $usuario = authenticator::validarJWT();
                if(isset($usuario)){
                    $message = ventas::obtenerVentas($usuario);
                    $success = true;
                }else{
                    $message = "Usuario invalido";
                }
//(GET) ventas: Si es admin muestra listado con todas las ventas, si es usuario solo las ventas de dicho usuario
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
