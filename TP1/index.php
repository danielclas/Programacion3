<?php
    require('pais.php');
    require('request.php');
    
    $tabla = "";

    if(isset($_POST["dato"]) && isset($_POST["criterio"])){
        $result = request::realizarRequest($_POST["dato"], (int)$_POST["criterio"]);
    }

    if(isset($result)){
        $tabla = hacerTabla($result);
    }else{
        $tabla = "Ningun resultado fue encontrado";
    }

    $vista = "
            <h3>Buscar paises por continente, region, idioma o capital</h3>
            <form action=\"index.php\" method=\"post\">
                <label for=\"continente\">Continente: </label>
                <input type=\"text\" name=\"dato\">       
                <input type=\"hidden\" name=\"criterio\" value=\"1\" />     
                <input type=\"submit\">
            </form>
            <form action=\"index.php\" method=\"post\">
                <label for=\"region\">Region: </label>
                <input type=\"text\"  name=\"dato\"> 
                <input type=\"hidden\" name=\"criterio\" value=\"2\"/>             
                <input type=\"submit\">
            </form>
            <form action=\"index.php\" method=\"post\">
                <label for=\"idioma\">Idioma: </label>
                <input type=\"text\" name=\"dato\"\>   
                <input type=\"hidden\" name=\"criterio\" value=\"3\"/>              
                <input type=\"submit\">
            </form>
            <form action=\"index.php\" method=\"post\">
                <label for=\"capital\">Capital: </label>
                <input type=\"text\" name=\"dato\"\>
                <input type=\"hidden\" name=\"criterio\" value=\"4\" />               
                <input type=\"submit\">
            </form>";

    echo $vista; 
    echo $tabla;

    function hacerTabla($result){
        $filas = "";
        
        foreach($result as $clave=>$pais){
            $p = new Pais($pais);
            $filas.= $p->modelar();
        }

        $tabla = "<table border=\"1px solid black\" style=\"width:100%\">
                  <caption>Datos de paises</caption>
                  <tr>
                    <th>Bandera</th>
                    <th>Nombre</th>
                    <th>Moneda</th>
                    <th>Continente</th>
                    <th>Poblacion</th>
                    <th>Idioma</th>
                    <th>Capital</th>
                   </tr>";

        $tabla.=$filas;
        $tabla.="</table>";

        return $tabla;
    }

    





    
    

    