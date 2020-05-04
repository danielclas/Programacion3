<?php

class Producto{

    public $producto;
    public $marca;
    public $precio;
    public $stock;
    public $foto;
    public $id;
    public static $path = './archivos/productos.json';

    public function __construct($obj){        

        $this->producto = $obj['producto'];        
        $this->marca = $obj['marca'];
        $this->precio = $obj['precio'];
        $this->stock = $obj['stock'];
        $this->id = time();
        $this->foto = helper::procesarImagen($this->id,$_FILES['foto']);

    }

    public static function esProductoValido($request){

        $props = ['producto','marca','precio','stock'];

        foreach($props as $key => $prop)
            if(!isset($request[$prop]))
                return false;          
        
        return isset($_FILES['foto']);
    }

    public static function hayStock($id,$cantidad){

        $productos = helper::leerArchivo(self::$path);

        foreach($productos as $key=>$producto)
            if($producto['id']==$id && $producto['stock']>=$cantidad && $cantidad>0)            
                return true;

        return false;
    }

    public static function actualizarStock($idProducto,$cantidad){

        $productos = helper::leerArchivo(self::$path);
        $productosActualizado = [];

        foreach($productos as $key=>$producto){
            if($idProducto == $producto['id']){
                $producto['stock']-=$cantidad;
            }
            array_push($productosActualizado,$producto);
        }

        file_put_contents(self::$path,'');
        
        $file = fopen(self::$path,'w');
        fwrite($file,json_encode($productosActualizado));
        fclose($file);

    }
}
?>