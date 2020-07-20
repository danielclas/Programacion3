<?php
namespace App\Utils;

class ResponseParser{

    public static function JsendResponse($status, $data){

        self::$respuesta = new \stdClass();
        self::$respuesta->status = $status;

        if ($status != "success") {
            self::$respuesta->message = $data;
        } else {
            self::$respuesta->data = $data;
        }

        return json_encode(self::$respuesta);
    }
}