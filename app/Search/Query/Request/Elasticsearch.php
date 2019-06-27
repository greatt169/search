<?php

namespace App\Search\Query\Request;

use App\Search\Entity\Interfaces\EntityInterface;
use Elasticsearch\Client;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\ListItem;

class Elasticsearch extends Engine
{

    public function __construct($engine, $index, EntityInterface $entity)
    {
        parent::__construct($engine, $index, $entity);
    }

    /**
     * @param Filter $filter
     * @return ListItem[]
     */
    public function postCatalogList(Filter $filter)
    {
        $params = [
            'index' => $this->entity->getIndexByAlias($this->index),
            'type' => $this->index,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'colors' => 'black'
                                ]
                            ],
                            [
                                'match' => [
                                    'colors' => 'white'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        /**
         * @var Client $client
         */
        $client = $this->entity->getClient();
        $results = $client->search($params);
        print_r($results);

        return [new ListItem()];
    }
}