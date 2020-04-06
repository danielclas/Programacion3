<?php
    require('IModelar.php');
    require('ubicacionGeografica.php');

    class Pais extends ubicacionGeografica implements IModelar{

        private $moneda;
        private $idioma;
        private $capital;
        private $bandera;

        function __construct($pais) {
            $this->nombre = $pais->name;
            $this->moneda = $pais->currencies[0]->name;
            $this->continente = $pais->region;
            $this->poblacion = $pais->population;
            $this->idioma = $pais->languages[0]->name;
            $this->capital = $pais->capital;
            $this->bandera = $pais->flag;
        }

        function modelar(){
            return "<tr>
                        <td style=\"text-align:center\"><img align\"middle\" src=\"{$this->bandera}\" height=\"35\" width=\"42\"></td>
                        <td>{$this->nombre}</td>
                        <td>{$this->moneda}</td>
                        <td>{$this->continente}</td>
                        <td>{$this->poblacion}</td>
                        <td>{$this->idioma}</td>
                        <td>{$this->capital}</td>
                    </tr>";
        }
    }    
?>