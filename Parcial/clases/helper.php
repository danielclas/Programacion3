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

    public static function procesarImagen($imagen){
        
        $arr = explode(".", $imagen['name']);
        $destino = './imagenes/' . time() . '.' . end($arr);

        move_uploaded_file($imagen['tmp_name'],$destino);

        try{
            // Open the original image
            $image = new Imagick();
            $image->readImage($destino);

            // Open the watermark
            $watermark = new Imagick();
            $watermark->readImage('./imagenes/watermark.png');

            // Overlay the watermark on the original image
            $image->compositeImage($watermark, imagick::COMPOSITE_OVER, 0, 0);
        }catch(Exception $e){

        }        

        return __DIR__ . $destino;
    }

}