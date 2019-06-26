<?php

namespace App\Search\Helpers;

use SwaggerUnAuth\Model\Error;

abstract class ApiError
{
    public static function returnError($message, $debug, $code)
    {
        return response(
            new Error([
                'application_error_code' => $code,
                'debug' => $debug,
                'message' => $message
            ]), $code
        );
    }
}