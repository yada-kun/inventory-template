<?php

namespace App\Traits;

trait HttpResponses {
    protected function success($data, $message = null, $code){

        return response()->json([
            'status' => 'Request was successful',
            'message' => $message,
            'data' => $data
        ],$code);
    }

    protected function error($message = null, $code){

        return response()->json([
            'status' => 'Error has occurred',
            'message' => $message
        ],$code);
    }


}