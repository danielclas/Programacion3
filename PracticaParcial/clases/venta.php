<?php

class venta{

    $idProducto;
    $cantidad;
    $idCliente;

    public function __construct($producto,$cantidad,$cliente){

        $this->idProducto=$producto;
        $this->cantidad = $cantidad;
        $this->idCliente = $cliente;
    }
}