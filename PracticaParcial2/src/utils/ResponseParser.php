<?php
namespace App\Utils;

class ResponseParser{

    public static function parse($success, $data){

        $response = new \StdClass();
        $response->status = $success ?? '';
        $response->data = $data ?? '';

        return json_encode($response);
    }
}