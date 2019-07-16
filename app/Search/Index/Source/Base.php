<?php

namespace App\Search\Index\Source;

use App\Search\Index\Interfaces\SourceInterface;
use SwaggerSearch\Model\SourceIndex;
use SwaggerSearch\ObjectSerializer;

abstract class Base implements SourceInterface
{
    /**
     * @var null | SourceIndex
     */
    protected $sourceIndex = null;

    protected $sourceData;

    /**
     * @var string
     */
    protected $indexName;

    /**
     * Base constructor.
     * @param $sourceLink
     */
    protected function __construct($sourceLink)
    {
        $this->sourceData = file_get_contents($sourceLink);
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
                json_decode($this->sourceData),
                SourceIndex::class, null
            );
            $this->sourceIndex = $sourceIndex;
        }
        return $this->sourceIndex;
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }
}