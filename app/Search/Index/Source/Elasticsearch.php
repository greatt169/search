<?php

namespace App\Search\Index\Source;

use App\Search\Index\Interfaces\SourceInterface;

class Elasticsearch implements SourceInterface
{
    protected $indexName = 'auto';

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
        $data = include_once('data.php');
        $objects = [];
        $mapping = $this->getAttributesMapping();

        foreach ($data as $dataItem) {
            $source = [];
            $sourceAttributes = [];
            $source['id'] = $dataItem['id'];
            foreach ($mapping as $code => $mappingItem) {
                $sourceAttribute = $mappingItem;

                $sourceAttribute['code'] = $code;
                $sourceAttribute['value'] = $dataItem[$code];

                $sourceAttributes[] = $sourceAttribute;
            }
            $source['attributes'] = $sourceAttributes;
            $objects[] = $source;
        }

        return $objects;
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }
}