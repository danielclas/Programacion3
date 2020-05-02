<?php

class cliente{

    public $id;
    public $nombre;
    public $dni;
    public $obraSocial;
    public $clave;
    public $tipo;

    public function __construct($obj){
        $this->id = time();    
        $this->nombre = $obj['nombre'];
        $this->dni = $obj['dni'];
        $this->obraSocial = $obj['obra_social'];
        $this->clave = $obj['clave'];
        $this->tipo = $obj['tipo'];
    }

    public static function esClienteValido($request){

        $props = ['nombre','dni','obra_social','clave','tipo'];
        
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