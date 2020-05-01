<?php

require_once './clases/usuario.php';
require_once './clases/helper.php';

$path_info = $_SERVER['PATH_INFO'] ?? NULL;
$request_method = $_SERVER['REQUEST_METHOD'] ?? NULL;


if(isset($path_info) && isset($request_method)){
    if($request_method == "POST"){
        switch($path_info){
            case "/signin":
                $isValid = usuario::isValidUser($_POST);
                if($isValid){
                    $usuario = new usuario($_POST);
                    usuario::writeToFile($usuario);
                }
            break;
            case "/login":
                $emailLogin = $_POST['email'] ?? NULL;
                $claveLogin = $_POST['clave'] ?? NULL;
                // $usuario = usuario::user_exists($emailLogin,$claveLogin);
                $usuario = new usuario("daniel@daniel.com","1234","daniel","clas","112233","admin");
                echo helper::formatResponse($usuario);
            break;
        }
    }
}

/*
1- POST signin: recibe email, clave, nombre, apellido, telefono y tipo (user, admin) y lo guarda en un archivo.
2- POST login: recibe email y clave y chequea que existan, 
si es así retorna un JWT de lo contrario informa el error (si el email o la clave están equivocados) .



*/