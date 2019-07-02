<?php

namespace App\Search\Index\Source;

use App\Search\Index\Interfaces\SourceInterface;
use SwaggerUnAuth\Model\ListItem;
use SwaggerUnAuth\Model\ListItemAttributes;
use SwaggerUnAuth\Model\ListItemAttributeValue;
use SwaggerUnAuth\Model\ListItemMultipleAttribute;
use SwaggerUnAuth\Model\ListItems;
use SwaggerUnAuth\ObjectSerializer;

class Elasticsearch implements SourceInterface
{
    protected $indexName = 'auto';
    protected $typeName = 'auto';


    public function getElementsForIndexing()
    {
        $sourceData = include_once('/var/www/public/data.php');
        /**
         * @var ListItems $listItems
         */
        $listItems = ObjectSerializer::deserialize(json_decode(json_encode($sourceData)), ListItems::class, null);

        $elementsForIndexing = [];
        $data = $listItems->getItems();

        /**
         * @var ListItem $dataItem
         */
        foreach ($data as $dataItem) {
            $source = [];
            $sourceAttributes = [];
            $singleAttributes = [];
            $multipleAttributes = [];
            $source['id'] = $dataItem->getId();

            /**
             * @var ListItemAttributes $attributes
             */
            $attributes = $dataItem->getAttributes();
            if($attributes !== null) {
                $singleAttributes = $attributes->getSingle();
                $multipleAttributes = $attributes->getMultiple();
            }

            /**
             * @var ListItemAttributeValue $singleAttribute
             */
            foreach ($singleAttributes as $attributeCode => $singleAttribute) {
                $sourceAttribute = [];
                foreach ($singleAttribute::getters() as $code => $method) {
                    $sourceAttribute[$code] = $singleAttribute->$method();
                }
                $sourceAttributes[$attributeCode] = $sourceAttribute;
            }

            /**
             * @var  ListItemMultipleAttribute $multipleAttribute
             */
            foreach ($multipleAttributes as $attributeCode => $multipleAttribute) {
                $sourceAttribute = [];
                $values = $multipleAttribute->getValues();
                /**
                 * @var ListItemAttributeValue $singleAttribute
                 */
                $sourceAttributeValues = [];
                foreach ($values as $singleAttribute) {
                    foreach ($singleAttribute::getters() as $code => $method) {
                        $sourceAttribute[$code] = $singleAttribute->$method();
                    }
                    $sourceAttributeValues[] = $sourceAttribute;
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

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->typeName;
    }
}