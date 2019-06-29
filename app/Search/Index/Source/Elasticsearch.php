<?php

namespace App\Search\Index\Source;

use App\Search\Index\Interfaces\SourceInterface;
use SwaggerUnAuth\Model\ListItem;
use SwaggerUnAuth\Model\ListItemAttributeValue;
use SwaggerUnAuth\Model\ListItems;
use SwaggerUnAuth\ObjectSerializer;

class Elasticsearch implements SourceInterface
{
    protected $indexName = 'auto';
    protected $typeName = 'auto';

    public function getAttributesMapping()
    {
        $mapping = [
            'model' => [
                'in_query' => true,
                'in_body' => true,
                'type' => 'string',
                'multiple' => false,
            ],
            'colors' => [
                'in_query' => true,
                'in_body' => true,
                'type' => 'string',
                'multiple' => true,
            ],
            'year' => [
                'in_query' => true,
                'in_body' => true,
                'type' => 'integer',
                'multiple' => false,
            ],
            'price' => [
                'in_query' => true,
                'in_body' => true,
                'type' => 'float',
                'multiple' => false,
            ],
            'model_logo' => [
                'in_query' => false,
                'in_body' => true,
                'type' => 'string',
                'multiple' => false,
            ]
        ];
        return $mapping;
    }


    public function getElementsForIndexing()
    {
        $sourceData = include_once('/var/www/public/data.php');
        /**
         * @var ListItems $listItems
         */
        $listItems = ObjectSerializer::deserialize(json_decode(json_encode($sourceData)), ListItems::class, null);

        $elementsForIndexing = [];
        $mapping = $this->getAttributesMapping();
        $data = $listItems->getItems();

        /**
         * @var ListItem $dataItem
         */
        foreach ($data as $dataItem) {
            $source = [];
            $sourceAttributes = [];
            $source['id'] = $dataItem->getId();
            foreach ($mapping as $code => $mappingItem) {
                $singleAttributes = [];
                $multipleAttributes = [];
                $attributes = $dataItem->getAttributes();
                if($attributes) {
                    $singleAttributes = $attributes->getSingle();
                    $multipleAttributes = $attributes->getMultiple();
                }
                $sourceAttribute = $mappingItem;
                $sourceAttribute['code'] = $code;

                if(array_key_exists($code, $singleAttributes)) {
                    $valueObject = $singleAttributes[$code];
                } elseif(array_key_exists($code, $multipleAttributes)) {
                    $valueObject = $multipleAttributes[$code];
                }

                /**
                 * @var ListItemAttributeValue $valueObject
                 */
                $sourceAttribute['value'] = $valueObject->getValue();
                $sourceAttributes[] = $sourceAttribute;
            }
            $source['attributes'] = $sourceAttributes;
            $elementsForIndexing[] = $source;
        }
        dump($elementsForIndexing);
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