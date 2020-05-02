<?php

 class Cliente{

    $id;
    $nombre;
    $dni;
    $obraSocial;
    $clave
    $tipo

    function __construct($obj){
        $this->id = $obj['id'];        
        $this->nombre = $obj['nombre'];
        $this->dni = $obj['dni'];
        $this->obraSocial = $obj['obraSocial'];
        $this->clave = $obj['clave'];
        $this->tipo = $obj['tipo'];
    }

    public static function esClienteValido($request){

        $props = ['id','nombre','dni','obraSocial','clave','tipo'];

        foreach($props as $key => $prop)
            if(!isset($request[$prop]))
                return false;          
        
        return self::devolverCliente('id',$request['id']) == NULL;
    }

    public static function devolverCliente($prop,$dato){

        $data = helper::readFromFile('./archivos/datos.json');
  
        foreach($data as $key=>$cliente)
            if(isset($cliente[$prop]) && $cliente[$prop]==$dato)
               return $cliente;         
        
        return NULL;
      }    
 }
?>