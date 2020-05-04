<?php

class venta{

    public $idProducto;
    public $cantidad;
    public $idCliente;

    public function __construct($producto,$cantidad,$cliente){
        $this->idProducto=$producto;
        $this->cantidad = $cantidad;
        $this->idCliente = $cliente;
    }

    public function registrarVenta(){

        $ventas = self::obtenerVentas();
        
        array_push($ventas,$this);

        $ventas = serialize($ventas);
        file_put_contents(__DIR__.'/.././archivos/ventas.txt',$ventas);
    }

    public static function obtenerVentas($usuario = NULL){

        $ventas = [];
        $path = __DIR__.'/.././archivos/ventas.txt';

        if(filesize($path)!=0){
            $temp = file_get_contents($path);
            $temp = unserialize($temp);
            
            if(!isset($usuario) || $usuario->tipo=='admin'){
                return $temp;
            } 
            else{
                foreach($temp as $key=>$venta){
                    if($venta->idCliente == $usuario->id){
                        array_push($ventas,$venta);
                    }
                }
            }            
        }

        return $ventas;
    }
}