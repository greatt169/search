<?php

namespace App\Search\Index\Source;

use App\Search\Index\Interfaces\SourceInterface;
use SwaggerUnAuth\Model\ListItem;
use SwaggerUnAuth\Model\SourceIndex;
use SwaggerUnAuth\ObjectSerializer;

class Elasticsearch implements SourceInterface
{
    protected $indexName = 'auto';



    public function getElementsForIndexing()
    {
        $sourceData = include_once('/var/www/public/data.php');
        /**
         * @var SourceIndex $sourceIndex
         */
        $sourceIndex = ObjectSerializer::deserialize(json_decode(json_encode($sourceData)), SourceIndex::class, null);

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
            $source['attributes'] = $sourceAttributes;
            $elementsForIndexing[] = $source;
        }
        return $elementsForIndexing;
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }
}