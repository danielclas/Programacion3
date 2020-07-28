<?php
namespace App\Utils;

class RequestValidator{

    public static function containsParams($data, $params){

        foreach($params as $param){
            if(!isset($data[$param]))
                return false;
        }

        return true;
    }
}