<?php

namespace App\Search\Index\Source;

use App\Helpers\Interfaces\SerializerInterface;
use App\Helpers\Serializer;
use App\Search\Index\Interfaces\SourceInterface;
use SwaggerUnAuth\Model\SourceIndex;
use SwaggerUnAuth\ObjectSerializer;

abstract class Base implements SourceInterface
{
    /**
     * @var null | SourceIndex
     */
    protected $sourceIndex = null;

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

    /**
     * @return null | SourceIndex
     */
    public function getSourceIndex()
    {
        /**
         * @var SourceIndex $sourceIndex
         */
        if($this->sourceIndex == null) {
            $sourceIndex = ObjectSerializer::deserialize(
                $this->serializer::__toArray($this->sourceData),
                SourceIndex::class, null
            );
            $this->sourceIndex = $sourceIndex;
        }
        return $this->sourceIndex;
    }
}