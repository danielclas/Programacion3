<?php

 class helper{

    static function formatResponse($obj, $error = ''){

        $response = new stdClass();
        $isset = isset($obj);
        
        $response->success = $isset;
        $response->data = $isset ? $obj->user_encode() : '';

        return json_encode($response);
    }

}