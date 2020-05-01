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
    
    public static function return_user($email){

      $data = usuario::readFromFile();

      foreach($data as $key=>$user)
          if($user['email']==$email)
             return $user;         
      
      return NULL;
    }

    public static function readFromFile(){

      $data = '[]';      
      $filesize = filesize('./archivos/datos.json');

      if($filesize != 0){
        $file = fopen('./archivos/datos.json', 'r');
        $data = fread($file,$filesize);
        fclose($file);
      }          

      return json_decode($data,true);
    }

    public static function writeToFile($user){
        
      $data = usuario::readFromFile();

      array_push($data,$user);

      $file = fopen('./archivos/datos.json', 'w');
      fwrite($file,json_encode($data));
      fclose($file);      
    }

    public static function isValidUser($request){

        $props = ['email','nombre','apellido','clave','telefono','tipo'];

        foreach($props as $key => $prop)
            if(!isset($request[$prop]))
                return false;          
        
        return true;
    }
}
?>