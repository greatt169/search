<?php

namespace App\UseCases\Catalog;

use SwaggerSearch\Model\ListItems;
use SwaggerSearch\ObjectSerializer;

class Items
{
    /**
     * @param ListItems $response
     * @return object|string
     */
    public function getResult($response)
    {
        $result = ObjectSerializer::sanitizeForSerialization($response);
        return $result;
    }
}