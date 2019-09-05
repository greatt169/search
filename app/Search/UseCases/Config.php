<?php

namespace App\Search\UseCases;

class Config
{
    public static function isProdMode($prodConfigCode = 'prod')
    {
        return getenv('APP_ENV') === $prodConfigCode;
    }
}