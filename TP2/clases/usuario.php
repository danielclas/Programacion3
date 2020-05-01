<?php

//POST signin: recibe email, clave, nombre, apellido, telefono y tipo (user, admin) y lo guarda en un archivo.

class usuario
{
    public $email;
    public $clave;
    public $nombre;
    public $apellido;
    public $telefono;
    public $tipo;
    
    public function __construct($user)
    {
        $this->email = $user['email'];
        $this->clave = $user['clave'];
        $this->nombre = $user['nombre'];
        $this->apellido = $user['apellido'];
        $this->telefono = $user['telefono'];
        $this->tipo = $user['tipo'];
    }

    public function user_encode(){

      $vars = get_object_vars($this);

      return json_encode($vars);
    }

    public static function user_exists($email,$clave){

      $data = usuario::readFromFile();

      foreach($data['usuarios'] as $key=>$user){
          if($user['email']==$email && $user['clave']==$clave){
            return true;
          }
      }

      return false;

    }

    public static function readFromFile(){

      $file = fopen('./archivos/datos.json', 'r');
      $data = fread($file,filesize('./archivos/datos.json'));

      fclose($file);

      return json_decode($data,true);
    }

    public static function writeToFile($user){
        
      $data = usuario::readFromFile();

      array_push($data['usuarios'],$user);

      $file = fopen('./archivos/datos.json', 'w');
      fwrite($file,json_encode($data));
      fclose($file);      
    }

    public static function isValidUser($request){

        $props = ['email','nombre','apellido','clave','telefono','tipo'];

        foreach($props as $key => $prop){
            if(!isset($request[$prop])){
              return false;
            }
        }

        return true;
    }
}
?>