<?php

namespace App\Search\Index\Source;

use SwaggerSearch\Model\DisplayListItem;
use SwaggerSearch\Model\DisplayListItemAttributeValue;
use SwaggerSearch\Model\DisplayListItemMultipleAttribute;
use SwaggerSearch\Model\DisplayListItemSingleAttribute;
use SwaggerSearch\Model\SourceIndex;
use SwaggerSearch\Model\SourceIndexMapping;

class Elasticsearch extends Base
{
    protected $indexName = 'auto';

    /**
     * Elasticsearch constructor.
     * @param $sourceLink
     */
    public function __construct($sourceLink)
    {
        parent::__construct($sourceLink);
    }

    public function getIndexSettings()
    {
        /**
         * @var SourceIndex $sourceIndex
         */
        $sourceIndex = $this->getSourceIndex();
        $settings = $sourceIndex->getIndexSettings();
        return $settings;
    }

    /**
     * @param DisplayListItemAttributeValue $attributeValue
     * @return string
     */
    protected function getAttributeVal($attributeValue) {
        $val = $attributeValue->getCode();
        if($val === null) {
            $val = $attributeValue->getValue();
        }
        return $val;
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
         * @var DisplayListItem $dataItem
         */
        foreach ($data as $dataItem) {
            $source = [];
            $searchData = [];
            $sourceAttributes = [];
            $source['id'] = $dataItem->getId();

            $singleAttributes = $dataItem->getSingleAttributes();
            $multipleAttributes = $dataItem->getMultipleAttributes();

            /**
             * @var DisplayListItemSingleAttribute $attribute
             */
            foreach ($singleAttributes as $attribute) {
                /**
                 * @var DisplayListItemAttributeValue $attributeValue
                 */
                $attributeCode = $attribute->getCode();
                $attributeValue = $attribute->getValue();
                if($attributeValue) {
                    $sourceAttributes[$attributeCode] = $this->getAttributeVal($attributeValue);
                    $searchData[$attribute->getCode()] = $attribute->getName() . ' ' . $attributeValue->getValue();
                }
            }

            /**
             * @var DisplayListItemMultipleAttribute $attribute
             */
            foreach ($multipleAttributes as $attribute) {
                $attributeCode = $attribute->getCode();
                $multipleAttributeValues = $attribute->getValues();
                $sourceAttributeValues = [];
                /**
                 * @var DisplayListItemAttributeValue $attributeValue
                 */
                foreach ($multipleAttributeValues as $attributeValue) {
                    if($attributeValue) {
                        $sourceAttributeValues[] = $this->getAttributeVal($attributeValue);
                        $searchData[$attribute->getCode()] = $attribute->getName() . ' ' . $attributeValue->getValue();
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