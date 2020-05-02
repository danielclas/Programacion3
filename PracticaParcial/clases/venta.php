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
        $path = './archivos/ventas.xxx';
        $filesize = filesize($path);        
        $ventas = [];

        if($filesize!=0){
            $ventas = self::obtenerVentas();
        }

        array_push($ventas,$this);
        $file = fopen($path, 'wb');
        fwrite($file,$ventas);
        fclose($file);
    }

    public static function obtenerVentas($usuario = NULL){

        $path = './archivos/ventas.xxx';
        $filesize = filesize($path);
        
        if($filesize==0){
            return [];
        }else{
            $file = fopen($path, 'rb');
            $data = fread($file,$filesize);
            $ventas = unserialize($data);
            fclose($file);

            if(!isset($usuario) || $usuario->tipo=='admin'){
                return $ventas;
            }else{
                $arr = [];
                foreach($ventas as $key=>$venta){
                    if($venta->idCliente == $usuario->id){
                        array_push($arr,$venta);
                    }
                }
                return $arr;
            }
        }

        return [];        
    }
}