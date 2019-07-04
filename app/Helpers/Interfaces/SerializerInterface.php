<?php

namespace App\Helpers\Interfaces;
/**
 * Interface SerializerInterface
 * @package App\Helpers\Interfaces
 */
interface SerializerInterface
{
    public static function __toArray($data): array;
}