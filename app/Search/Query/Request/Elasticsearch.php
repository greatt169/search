<?php

namespace App\Search\Query\Request;

use App\Exceptions\ApiException;
use App\Search\Entity\Interfaces\EntityInterface;
use Elasticsearch\Client;
use Exception;
use SwaggerUnAuth\Model\InputFilter;
use SwaggerUnAuth\Model\InputFilterParam;
use SwaggerUnAuth\Model\InputFilterRangeParam;
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
            unset($term);
        }

        $rangeParams = $filter->getRangeParams();
        /**
         * @var  InputFilterRangeParam $rangeParam
         */
        foreach ($rangeParams as $rangeParam) {
            $paramCode = $rangeParam->getCode();
            $minValue = $rangeParam->getMinValue();
            $maxValue = $rangeParam->getMaxValue();

            $term[] = [
                'range' => [
                    $paramCode => [
                        'gte' => $minValue,
                        'lte' => $maxValue,
                    ]
                ]
            ];

            $elasticFilter['bool']['must'][] = $term;
        }

        return $elasticFilter;
    }

    /**
     * @param InputFilter $filter
     * @return ListItem[]
     * @throws ApiException
     */
    public function postCatalogList(InputFilter $filter)
    {
        try {
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
        } catch (Exception $exception) {
            throw new ApiException(class_basename($exception), $exception->getMessage(), $exception->getCode());
        }

        return [new ListItem()];
    }
}