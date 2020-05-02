<?php

 class helper{

    static function formatResponse($message,$success){

        $response = new stdClass();
        
        $response->success = $success;
        $response->data = $message;

        return json_encode($response);
    }

}