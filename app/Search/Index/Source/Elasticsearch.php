<?php

namespace App\Search\Index\Source;

use SwaggerSearch\Model\ListItem;
use SwaggerSearch\Model\SourceIndex;
use SwaggerSearch\Model\SourceIndexMapping;

class Elasticsearch extends Base
{
    protected $indexName = 'auto';

    public function __construct()
    {
        parent::__construct();
    }

    public function getElementsForIndexing()
    {
        /**
         * @var SourceIndex $sourceIndex
         */
        $sourceIndex = $this->getSourceIndex();

        $elementsForIndexing = [];
        $data = $sourceIndex->getItems();

        /**
         * @var ListItem $dataItem
         */
        foreach ($data as $dataItem) {
            $source = [];
            $sourceAttributes = [];
            $source['id'] = $dataItem->getId();

            $singleAttributes = $dataItem->getSingleAttributes();
            $multipleAttributes = $dataItem->getMultipleAttributes();

            foreach ($singleAttributes as $attributeCode => $value) {
                $sourceAttributes[$attributeCode] = $value;
            }

            foreach ($multipleAttributes as $attributeCode => $multipleAttributeValues) {
                $sourceAttributeValues = [];
                foreach ($multipleAttributeValues as $value) {
                    $sourceAttributeValues[] = $value;
                }
                $sourceAttributes[$attributeCode] = $sourceAttributeValues;
            }
            $source['type'] = $dataItem->getType();
            $source['attributes'] = $sourceAttributes;
            $elementsForIndexing[] = $source;
        }
        return $elementsForIndexing;
    }

    public function getMappingForIndexing()
    {
        /**
         * @var SourceIndex $sourceIndex
         */
        $sourceIndex = $this->getSourceIndex();
        $mappingParams = [];
        $mapping = $sourceIndex->getMapping();
        /**
         * @var SourceIndexMapping $attributeMapping
         */
        foreach ($mapping as $attributeCode => $attributeMapping) {
            $mappingParams[$attributeCode] = [
                'type' => $attributeMapping->getType()
            ];
        }

        return $mappingParams;
    }
}