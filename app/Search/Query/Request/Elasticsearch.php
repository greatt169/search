<?php

namespace App\Search\Query\Request;

use App\Exceptions\ApiException;
use App\Search\Entity\Interfaces\EntityInterface;
use Elasticsearch\Client;
use Exception;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\FilterParam;
use SwaggerUnAuth\Model\FilterRangeParam;
use SwaggerUnAuth\Model\FilterValue;
use SwaggerUnAuth\Model\ListItem;
use SwaggerUnAuth\Model\ListItems;
use SwaggerUnAuth\Model\SelectedFields;
use SwaggerUnAuth\Model\Sort;

class Elasticsearch extends Engine
{

    public function __construct($engine, $index, EntityInterface $entity)
    {
        parent::__construct($engine, $index, $entity);
    }

    /**
     * @param Filter $filter
     * @return array
     */
    protected function getEngineConvertedFilter(Filter $filter)
    {
        $elasticFilter = [];
        $selectParams = $filter->getSelectParams();
        /**
         * @var FilterParam $selectParam
         */
        foreach ($selectParams as $selectParam) {
            $paramCode = $selectParam->getCode();
            $values = $selectParam->getValues();
            /**
             * @var FilterValue $value
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
         * @var  FilterRangeParam $rangeParam
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
     * @param Filter|null $filter
     * @param Sort|null $sort
     * @param SelectedFields|null $selectedFields
     * @return ListItems
     * @throws ApiException
     */
    public function postCatalogList(Filter $filter = null, Sort $sort = null, SelectedFields $selectedFields = null) : ListItems
    {
        try {

            $requestBody = [];
            if($filter !== null) {
                $requestBody['query'] = [
                    'constant_score' => [
                        'filter' => $this->getEngineConvertedFilter($filter)
                    ]
                ];
            }
            $params = [
                'index' => $this->entity->getIndexByAlias($this->index),
                'body' => $requestBody
            ];
            /**
             * @var Client $client
             */
            $client = $this->entity->getClient();
            $results = $client->search($params);
            $total = $results['hits']['total']['value'];
            $hits = $results['hits']['hits'];
            $items = [];
            foreach ($hits as $hit) {
                $items[] = $this->entity->getConvertedEngineData($hit);
            }
            $response = new ListItems();
            $response->setTotal($total);
            $response->setItems($items);
        } catch (Exception $exception) {
            throw new ApiException(class_basename($exception), $exception->getMessage(), $exception->getCode());
        }

        return $response;
    }
}