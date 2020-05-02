<?php

 class helper{

    static function formatResponse($message,$success){

        $response = new stdClass();
        
        $response->success = $success;
        $response->data = $message;

        return json_encode($response);
    }

    public static function leerArchivo($path){

        $data = '[]';      
        $filesize = filesize($path);
  
        if($filesize != 0){
          $file = fopen($path, 'r');
          $data = fread($file,$filesize);
          fclose($file);
        }          
  
        return json_decode($data,true);
    }      
    
    //Debe devolver true o false
    public static function guardarEnArchivo($path,$obj){
          
        $data = self::leerArchivo($path);

        try{
            array_push($data,$obj);  
            $file = fopen($path, 'w');
            fwrite($file,json_encode($data));
            fclose($file); 
        }catch(Exception $e){
            return false;
        }

        return true;             
      }

    public static function procesarImagen($id,$imagen){

        $watermark = new watermark($imagen['tmp_name']);
        $watermark->setFontSize(48)
        ->setRotate(30)
        ->setOpacity(.4);

        $arr = explode(".", $imagen['name']);
        $destino = './imagenes/' . $id . end($arr);

        $watermark->withText('Parcial Programacion', $destino);

        return $destino;
    }

}