<?php

namespace App\Search\Index\Source;

use App\Search\Index\Interfaces\SourceInterface;

class Elasticsearch implements SourceInterface
{

    public function getElementsForIndexing()
    {
        $data = include_once('data.php');
        $objects = [];
        foreach ($data as $dataItem) {
            $source = [
                'id' => $dataItem['id'],
                'attributes' => [
                    [
                        'in_query' => true,
                        'in_body' => true,
                        'code' => 'model',
                        'type' => 'string',
                        'multiple' => false,
                        'value' => $dataItem['model'],
                    ],
                    [
                        'in_query' => true,
                        'in_body' => true,
                        'code' => 'colors',
                        'type' => 'string',
                        'multiple' => true,
                        'value' => $dataItem['colors']
                    ],
                    [
                        'in_query' => true,
                        'in_body' => true,
                        'code' => 'year',
                        'type' => 'integer',
                        'multiple' => false,
                        'value' => $dataItem['year']
                    ],
                    [
                        'in_query' => true,
                        'in_body' => true,
                        'code' => 'price',
                        'type' => 'float',
                        'multiple' => false,
                        'value' => $dataItem['price']
                    ],
                    [
                        'in_query' => false,
                        'in_body' => true,
                        'code' => 'model_logo',
                        'type' => 'string',
                        'multiple' => false,
                        'value' => $dataItem['model_logo']
                    ]
                ]
            ];
            $objects[] = $source;
        }

        return $objects;
    }
}