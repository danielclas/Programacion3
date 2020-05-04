<?php

class pizza{

    public $tipo;
    public $precio;
    public $stock;
    public $sabor;
    public $foto;
    public static $path = './archivos/pizzas.json';

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
        $pizzas = helper::leerArchivo(self::$path);

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

    public static function mostrarPizzas($esEncargado){

        $pizzas = helper::leerArchivo(self::$path);
        $arr = [];

        if(empty($pizzas)){
            return '[]';
        }else{
            if($esEncargado){
                return $pizzas;
            }else{
                foreach($pizzas as $key=>$pizza){
                    //pop key
                    unset($pizza['stock']);
                    array_push($arr,$pizza);
                }
            }
        }

        return $arr;

    }    

    public static function hayStock($tipo,$sabor){

        $pizzas = helper::leerArchivo(self::$path);

        foreach($pizzas as $key=>$pizza)
            if($pizza['tipo']==$tipo && $pizza['sabor']==$sabor && $pizza['stock']>=1)            
                return true;

        return false;
    }

    public static function actualizarStock($tipo,$sabor){

        $pizzas = helper::leerArchivo(self::$path);
        $pizzasActualizado = [];
        $precio = 0;

        foreach($pizzas as $key=>$pizza){
            if($pizza['tipo']==$tipo && $pizza['sabor']==$sabor){
                $pizza['stock']--;
                $precio = $pizza['precio'];
            }
            array_push($pizzasActualizado,$pizza);
        }

        file_put_contents(self::$path,'');
        
        $file = fopen(self::$path,'w');
        fwrite($file,json_encode($pizzasActualizado));
        fclose($file);

        return $precio;

    }
 }
?>