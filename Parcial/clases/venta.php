<?php

class venta{

    public $email;
    public $tipo;
    public $sabor;
    public $monto;
    public $fecha;
    public static $path = './archivos/ventas.json';

    public function __construct($email,$tipo,$sabor,$monto){
        $this->email=$email;
        $this->tipo = $tipo;
        $this->sabor = $sabor;
        $this->monto = $monto;
        $this->fecha = date("F j, Y, g:i a");
    }

    public function registrarVenta(){

        return helper::guardarEnArchivo(self::$path,$this);    
    }

    // Si se realiza la venta restar el stock a la pizza y guardar la venta archivo
    //         // ventas.xxx el email, tipo, sabor, monto y fecha.

    public static function obtenerVentas($esEncargado,$email){

        $ventas = helper::leerArchivo(self::$path);
        $montoTotal = 0;
        $cantidadDeVentas = 0;
        $compras = [];

        if(!empty($ventas)){
            foreach($ventas as $key=>$venta){
                if($esEncargado){
                    $montoTotal+=$venta['monto'];
                }else if($email==$venta['email']){
                    array_push($compras,$venta);
                }
            }
            $cantidadDeVentas = count($ventas);
        }

        return $esEncargado ? array('monto'=>$montoTotal,'cantidadDeVentas'=>$cantidadDeVentas) : $compras;
    }
}
