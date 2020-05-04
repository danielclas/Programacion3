<?php

class cliente{

    public $email;
    public $clave;
    public $tipo;

    public function __construct($obj){
        
        $this->email = $obj['email'];
        $this->clave = $obj['clave'];
        $this->tipo = $obj['tipo'];
    }

    public static function esClienteValido($request){

        $props = ['email','clave','tipo'];
        
        foreach($props as $key => $prop)
            if(!isset($request[$prop]))
                return false;          
        
        return true;
    }

    public static function devolverCliente($prop,$dato){

        $data = helper::leerArchivo('./archivos/datos.json');
  
        foreach($data as $key=>$cliente)
            if(isset($cliente[$prop]) && $cliente[$prop]==$dato)
               return $cliente;         
        
        return NULL;
      }    
 }
?>