<?php
namespace App\Utils;

class DateParser{

    public static function parse($date){

        //Date format: 2020-07-28 17:00
        return new DateTime($date);    
    }
}