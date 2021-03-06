<?php

namespace App\Helpers;

use App\Helpers\Interfaces\SerializerInterface;

class Serializer implements SerializerInterface
{
    public static function __toArray($data)
    {
        return json_decode(json_encode($data));
    }
}