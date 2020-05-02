<?php

 class Cliente{

    $id;
    $nombre;
    $dni;
    $obraSocial;
    $clave
    $tipo

    function __construct($cliente){
        $this->id=$client['id'];        
        $this->nombre=$client['nombre'];
        $this->dni=$client['dni'];
        $this->obraSocial=$client['obraSocial'];
        $this->clave=$client['clave'];
        $this->tipo=$client['tipo'];
    }

    public static function esClienteValido($request){

        $props = ['id','nombre','dni','obraSocial','clave','tipo'];

        foreach($props as $key => $prop)
            if(!isset($request[$prop]))
                return false;          
        
        return cliente::devolverCliente('id',$request['id']) == NULL;
    }

    public static function devolverCliente($prop,$dato){

        $data = cliente::readFromFile();
  
        foreach($data as $key=>$cliente)
            if($cliente[$prop]==$dato)
               return $cliente;         
        
        return NULL;
      }

    public static function leerArchivo(){

        $data = '[]';      
        $filesize = filesize('./archivos/datos.json');
  
        if($filesize != 0){
          $file = fopen('./archivos/datos.json', 'r');
          $data = fread($file,$filesize);
          fclose($file);
        }          
  
        return json_decode($data,true);
      }

    //Debe devolver true o false
      public static function guardarEnArchivo($cliente){
          
        $data = cliente::leerArchivo();

        array_push($data,$cliente);
  
        $file = fopen('./archivos/datos.json', 'w');

        fwrite($file,json_encode($data));
        fclose($file);      
      }
 }
?>