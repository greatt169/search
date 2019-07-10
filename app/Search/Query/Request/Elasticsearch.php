<?php

namespace App\Search\Query\Request;

use App\Exceptions\ApiException;
use App\Search\Entity\Interfaces\EntityInterface;
use Elasticsearch\Client;
use Exception;
use SwaggerSearch\Model\Filter;
use SwaggerSearch\Model\FilterParam;
use SwaggerSearch\Model\FilterRangeParam;
use SwaggerSearch\Model\FilterValue;
use SwaggerSearch\Model\ListItems;
use SwaggerSearch\Model\SelectedFields;
use SwaggerSearch\Model\Sorts;

class Elasticsearch extends Engine
{
    /**
     * Elasticsearch constructor.
     * @param $engine
     * @param $index
     * @param EntityInterface $entity
     * @throws ApiException
     */
    public function __construct($engine, $index, EntityInterface $entity)
    {
        parent::__construct($engine, $index, $entity);
    }

    /**
     * @param Sorts $sorts
     * @return array
     */
    public function getEngineConvertedSorts(Sorts $sorts): array
    {
        $elasticSort = [];
        $sortItems = $sorts->getItems();
        foreach ($sortItems as $sort) {
            $elasticSort[] = [$sort->getField() => ['order' => $sort->getOrder()]];
        }
        return $elasticSort;
    }

    /**
     * @param SelectedFields $selectedFields
     * @return array
     */
    public function getEngineConvertedSelectedFields(SelectedFields $selectedFields): array
    {
        return $selectedFields->getFields();
    }

    /**
     * @param Filter $filter
     * @return array
     */
    public function getEngineConvertedFilter(Filter $filter): array
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
     * @param Sorts|null $sorts
     * @param SelectedFields|null $selectedFields
     * @param int $page
     * @param int $pageSize
     * @return ListItems
     * @throws ApiException
     */
    public function postCatalogList(Filter $filter = null, Sorts $sorts = null, SelectedFields $selectedFields = null, $page = 1, $pageSize = 20) : ListItems
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
            if($sorts !== null) {
                $requestBody['sort'] = $this->getEngineConvertedSorts($sorts);
            }
            if($selectedFields !== null) {
                $requestBody['_source'] = $this->getEngineConvertedSelectedFields($selectedFields);
            }

            $from = $page * $pageSize - $pageSize;
            $requestBody['size'] = $pageSize;
            $requestBody['from'] = $from;

            $params = [
                'index' => $this->index,
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