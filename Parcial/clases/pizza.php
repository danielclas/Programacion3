<?php

class pizza{

    public $tipo;
    public $precio;
    public $stock;
    public $sabor;
    public $foto;

    public function __construct($obj){
        
        $this->tipo = $obj['tipo'];
        $this->precio = $obj['precio'];
        $this->stock = $obj['stock'];
        $this->sabor = $obj['sabor'];
        $this->foto = helper::procesarImagen($_FILES['foto']);
    }

    //Corrobora que todos los datos enviados en el request sean validos
    public static function esPizzaValida($request){

        $props = ['tipo','precio','stock','sabor'];
        $pizzas = helper::leerArchivo('./archivos/pizzas.json');

        foreach($props as $key => $prop)
            if(!isset($request[$prop]))
                return false;          
        
        $tipo = $request['tipo'];
        $sabor = $request['sabor'];
        if($tipo!='molde' && $tipo!='piedra') return false;
        if($sabor!='jamon' && $sabor!='napo' && $sabor!='muzza') return false;

        if(!empty($pizzas))
            foreach($pizzas as $key=>$pizza)
                if($pizza['tipo']==$request['tipo'] && $pizza['sabor']==$request['sabor'])
                    return false;

        return isset($_FILES['foto']);
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