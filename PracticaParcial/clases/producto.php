<!-- 3. (POST) stock: (Solo para admin). Recibe producto (vacuna o medicamento), marca, precio, stock y foto y
lo guarda en un archivo en formato JSON, a la imagen la guarda en la carpeta imágenes. Generar un
identificador (id) único para cada producto -->
<?php

class Producto{

    public $producto;
    public $marca;
    public $precio;
    public $stock;
    public $foto;
    public $id;

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

    public static function restarStock($id,$cantidad){

        $productos = helper::leerArchivo('./archivos/productos.json');

        foreach($productos as $key=>$producto){
            if($producto['id']==$id && $producto['stock']>=$cantidad){
                $producto['stock']-=$cantidad;
                helper::guardarEnArchivo('./archivos/productos.json',$productos);
                return true;

            }         
        }

        return false;
    }
}
?>