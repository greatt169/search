<?php

namespace App\Search\Index\Source;

use App\Search\Index\Interfaces\SourceInterface;
use SwaggerSearch\Model\SourceIndex;
use SwaggerSearch\Model\SourceItems;
use SwaggerSearch\ObjectSerializer;

abstract class Base implements SourceInterface
{
    /**
     * @var null | SourceIndex
     */
    protected $sourceIndex = null;

    /**
     * @var null | SourceItems
     */
    protected $sourceItems = null;

    /**
     * @var null | string
     */
    protected $sourceIndexLink = null;

    /**
     * @var string
     */
    protected $sourceItemsLink = '';

    /**
     * @var string
     */
    protected $indexName;

    /**
     * Base constructor.
     * @param $sourceItemsLink
     * @param null $sourceIndexLink
     */
    protected function __construct($sourceItemsLink, $sourceIndexLink = null)
    {
        $this->sourceItemsLink = $sourceItemsLink;
        $this->sourceIndexLink = $sourceIndexLink;
    }

    /**
     * @param $fileLink
     * @param $swaggerModelName
     * @return array|object|null
     */
    protected function getSwaggerModelByFile($fileLink, $swaggerModelName)
    {
        $swaggerObject = ObjectSerializer::deserialize(
            json_decode(file_get_contents($fileLink)),
            $swaggerModelName, null
        );
        return $swaggerObject;
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
            $sourceIndex = $this->getSwaggerModelByFile($this->sourceIndexLink, SourceIndex::class);
            $this->sourceIndex = $sourceIndex;
        }
        return $this->sourceIndex;
    }

    /**
     * @return null | SourceItems
     */
    public function getSourceItems()
    {
        /**
         * @var SourceItems $sourceItems
         */
        if($this->sourceIndex == null) {
            $sourceItems = $this->getSwaggerModelByFile($this->sourceItemsLink, SourceItems::class);
            $this->sourceItems = $sourceItems;
        }
        return $this->sourceItems;
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }
}