<?php

namespace App\Search\Index\Source;

use App\Helpers\Interfaces\SerializerInterface;
use App\Helpers\Serializer;
use App\Search\Index\Interfaces\SourceInterface;

abstract class Base implements SourceInterface
{
    protected $sourceData;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    protected function __construct(SerializerInterface $serializer = null)
    {
        $this->sourceData = include_once('/var/www/public/data.php');
        if($serializer !== null) {
            $this->serializer = $serializer;
        } else {
            $this->serializer = new Serializer();
        }
    }
}