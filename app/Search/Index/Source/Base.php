<?php

namespace App\Search\Index\Source;

use App\Search\Index\Interfaces\SourceInterface;

abstract class Base implements SourceInterface
{
    protected $sourceData;

    protected function __construct()
    {
        $this->sourceData = include_once('/var/www/public/data.php');
    }
}