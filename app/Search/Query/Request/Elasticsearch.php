<?php

namespace App\Search\Query\Request;

use App\Search\Entity\Interfaces\EntityInterface;
use Elasticsearch\Client;
use SwaggerUnAuth\Model\InputFilter;
use SwaggerUnAuth\Model\InputFilterParam;
use SwaggerUnAuth\Model\InputFilterValue;
use SwaggerUnAuth\Model\ListItem;

class Elasticsearch extends Engine
{

    public function __construct($engine, $index, EntityInterface $entity)
    {
        parent::__construct($engine, $index, $entity);
    }

    /**
     * @param InputFilter $filter
     * @return array
     */
    protected function getEngineConvertedFilter(InputFilter $filter)
    {
        $elasticFilter = [];
        $selectParams = $filter->getSelectParams();

        /**
         * @var InputFilterParam $selectParam
         */
        foreach ($selectParams as $selectParam) {
            $paramCode = $selectParam->getCode();
            $values = $selectParam->getValues();
            /**
             * @var InputFilterValue $value
             */

            $term = [];
            foreach ($values as $value) {
                $paramValue = $value->getValue();
                $term ['bool']['should'][] = [
                    'match' => [
                        $paramCode => $paramValue
                    ]
                ];
            }
            $elasticFilter['bool']['must'][] = $term;
        }
        return $elasticFilter;
    }

    /**
     * @param InputFilter $filter
     * @return ListItem[]
     */
    public function postCatalogList(InputFilter $filter)
    {
        $params = [
            'index' => $this->entity->getIndexByAlias($this->index),
            'type' => $this->index,
            'body' => [
                'query' => [
                    'constant_score' => [
                        'filter' => $this->getEngineConvertedFilter($filter)
                    ]
                ]
            ]
        ];
        /**
         * @var Client $client
         */
        $client = $this->entity->getClient();
        $results = $client->search($params);
        //print_r($this->getEngineConvertedFilter($filter));
        print_r($results);
        return [new ListItem()];
    }
}