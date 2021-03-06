<?php

namespace App\Search\Index\Source;

use SwaggerSearch\Model\ListItem;
use SwaggerSearch\Model\ListItemAttributeValue;
use SwaggerSearch\Model\ListItemMultipleAttribute;
use SwaggerSearch\Model\ListItemSingleAttribute;
use SwaggerSearch\Model\SourceIndex;
use SwaggerSearch\Model\SourceIndexMapping;
use SwaggerSearch\ObjectSerializer;

class Elasticsearch extends Base
{
    public function __construct($indexName, $dataLink, $sourceIndexLink = null)
    {
        parent::__construct($indexName, $dataLink, $sourceIndexLink);
    }

    public function getIndexSettings()
    {
        /**
         * @var SourceIndex $sourceIndex
         */
        $sourceIndex = $this->getSourceIndex();
        $settings = $sourceIndex->getSettings();
        return $settings;
    }

    /**
     * @param ListItemAttributeValue $attributeValue
     * @return string
     */
    protected function getAttributeVal($attributeValue) {
        $val = $attributeValue->getCode();
        if($val === null) {
            $val = $attributeValue->getValue();
        }
        return $val;
    }

    public function getElementsForIndexing($rawItems)
    {
        $elementsForIndexing = [];
        /**
         * @var ListItem $dataItem
         */
        foreach ($rawItems as $rawItem) {
            /**
             * @var ListItem $dataItem
             */
            $dataItem = ObjectSerializer::deserialize(json_decode(json_encode($rawItem)), ListItem::class);
            $source = [];
            $searchData = [];
            $sourceAttributes = [];
            $source['id'] = $dataItem->getId();

            $singleAttributes = $dataItem->getSingleAttributes();
            $multipleAttributes = $dataItem->getMultipleAttributes();

            /**
             * @var ListItemSingleAttribute $attribute
             */
            foreach ($singleAttributes as $attributeCode => $attribute) {
                /**
                 * @var ListItemAttributeValue $attributeValue
                 */
                $attributeValue = $attribute->getValue();
                if($attributeValue) {
                    $sourceAttributes[$attributeCode] = $this->getAttributeVal($attributeValue);
                    $searchData[$attributeCode] = $attribute->getName() . ' ' . $attributeValue->getValue();
                }
            }

            /**
             * @var ListItemMultipleAttribute $attribute
             */
            foreach ($multipleAttributes as $attributeCode => $attribute) {
                $multipleAttributeValues = $attribute->getValues();
                $sourceAttributeValues = [];
                /**
                 * @var ListItemAttributeValue $attributeValue
                 */
                foreach ($multipleAttributeValues as $attributeValue) {
                    if($attributeValue) {
                        $sourceAttributeValues[] = $this->getAttributeVal($attributeValue);
                        $searchData[$attributeCode] = $attribute->getName() . ' ' . $attributeValue->getValue();
                    }
                }
                $sourceAttributes[$attributeCode] = $sourceAttributeValues;
            }
            $source['attributes'] = $sourceAttributes;
            $rawData = serialize($dataItem);
            $source['raw_data'] = $rawData;
            $source['search_data'] = $searchData;
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

        if(!$mapping) {
            return null;
        }

        /**
         * @var SourceIndexMapping $attributeMapping
         */
        foreach ($mapping as $attributeCode => $attributeMapping) {
            $mappingParams[$attributeCode] = [
                'type' => $attributeMapping->getType()
            ];
            $mappingParams['search_data']['properties'][$attributeCode] = [
                'type' => 'text',
                "analyzer" => 'default'
            ];
            $mappingParams['ts'] = [
                'type' => 'integer'
            ];
        }
        return $mappingParams;
    }
}