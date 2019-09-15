<?php

namespace App\Search\Query\Request;

use App\Events\Search\NewFeedReindexEvent;
use App\Events\Search\NewFeedUpdateEvent;
use App\Exceptions\ApiException;
use App\Helpers\Timer;
use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;
use App\Search\Entity\Interfaces\EntityInterface;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
use App\Search\Index\Manager\Elasticsearch as ElasticsearchManager;
use App\Search\UseCases\Errors\Error;
use Elasticsearch\Client;
use Exception;
use SwaggerSearch\Model\ActionSuccessResult;
use SwaggerSearch\Model\Aggregation;
use SwaggerSearch\Model\Aggregations;
use SwaggerSearch\Model\DisplayFilter;
use SwaggerSearch\Model\Filter;
use SwaggerSearch\Model\FilterParam;
use SwaggerSearch\Model\FilterRangeParam;
use SwaggerSearch\Model\FilterValue;
use SwaggerSearch\Model\ListItems;
use SwaggerSearch\Model\ReindexResponse;
use SwaggerSearch\Model\Search;
use SwaggerSearch\Model\Sorts;

class Elasticsearch extends Engine
{
    private $jobAddedInReindexQueueMessage = 'Job has added in reindexing queue';
    private $jobAddedInUpdateQueueMessage = 'Job has added in updating queue';
    private $indexElementDeletedMessageTemplate = 'Element has deleted. Index: %s, Element id: %s';
    private $rangePropAggsPrefix = 'range_';

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
     * @param Search $search
     * @return array
     */
    public function getEngineConvertedSearch(Search $search): array
    {
        $searchFields = $search->getFields();
        $fields = [];
        foreach ($searchFields as $searchField) {
            $fields[] = 'search_data.' . $searchField;
        }
        $elasticSearch = [
            'multi_match' => [
                'query' => $search->getQuery(),
                'fields' => $fields
            ],
        ];
        return $elasticSearch;
    }

    /**
     * @param Search|null $search
     * @param Filter|null $filter
     *
     * @return array
     */
    public function getQuery(Search $search = null, Filter $filter = null)
    {
        $query = [];
        if ($filter !== null && $search !== null) {
            $query = [
                'bool' => [
                    'must' => [
                        $this->getEngineConvertedSearch($search),
                        $this->getEngineConvertedFilter($filter)
                    ]
                ]
            ];
        }
        if ($filter === null && $search !== null) {
            $query = $this->getEngineConvertedSearch($search);
        }
        if ($filter !== null && $search === null) {
            $query = [
                'constant_score' => [
                    'filter' => $this->getEngineConvertedFilter($filter)
                ]
            ];
        }
        return $query;
    }

    /**
     * @param Search|null $search
     * @param Filter|null $filter
     * @param Aggregations|null $aggregations
     * @param Sorts|null $sorts
     * @param int $page
     * @param int $pageSize
     * @return ListItems
     * @throws ApiException
     */
    public function postCatalogList(Search $search = null, Filter $filter = null, Aggregations $aggregations = null, Sorts $sorts = null, $page = 1, $pageSize = 20): ListItems
    {
        try {
            $requestBody = [];
            $query = $this->getQuery($search, $filter);
            if (!empty($query)) {
                $requestBody['query'] = $query;
            }
            if ($sorts !== null) {
                $requestBody['sort'] = $this->getEngineConvertedSorts($sorts);
            }
            $requestBody['_source'] = ['raw_data'];
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


            if ($aggregations !== null) {
                $aggregationFilter = $this->getAggregationFilter($aggregations, $filter);
                $response->setFilter($aggregationFilter);
            }

        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage(), Error::CODE_INTERNAL_SERVER_ERROR);
        }
        return $response;
    }

    /**
     * @param string $index
     * @param string $dataLink
     * @param $settingsLink
     * @return mixed
     * @throws ApiException
     */
    public function reindex(string $index, string $dataLink, $settingsLink): ReindexResponse
    {
        $jobId = uniqid();
        $indexer = new ElasticsearchManager(
            new ElasticsearchSource($index, $dataLink, $settingsLink),
            new ElasticsearchEntity()
        );
        event(new NewFeedReindexEvent($jobId, $this->engine, $indexer));
        $reindexResponse = new ReindexResponse(
            [
                'job_id' => $jobId,
                'message' => $this->jobAddedInReindexQueueMessage
            ]
        );
        return $reindexResponse;
    }

    /**
     * @param string $index
     * @param string $dataLink
     * @return mixed
     * @throws ApiException
     */
    public function update(string $index, string $dataLink): ReindexResponse
    {
        $jobId = uniqid();
        $indexer = new ElasticsearchManager(
            new ElasticsearchSource($index, $dataLink),
            new ElasticsearchEntity()
        );
        event(new NewFeedUpdateEvent($jobId, $this->engine, $indexer));
        $reindexResponse = new ReindexResponse(
            [
                'job_id' => $jobId,
                'message' => $this->jobAddedInUpdateQueueMessage
            ]
        );
        return $reindexResponse;
    }

    /**
     * @param string $id
     * @return mixed|void
     * @throws ApiException
     */
    public function deleteElement(string $id)
    {
        /**
         * @var Client $client
         */
        $client = $this->entity->getClient();
        try {
            $result = $client->delete(
                [
                    'id' => $id,
                    'index' => $this->index
                ]
            );
            $resultData = [
                'code' => $result['result'],
                'message' => sprintf(
                    $this->indexElementDeletedMessageTemplate,
                    $result['_index'],
                    $result['_id']
                )
            ];

            $successResult = new ActionSuccessResult($resultData);
            return $successResult;
        } catch (\Throwable $exception) {
            throw new ApiException($exception->getMessage(), Error::CODE_BAD_REQUEST);
        }
    }

    /**
     * @param $arParamsAggregations
     * @return array
     */
    protected function getAggregations($arParamsAggregations)
    {
        $arAggregations = [];
        foreach ($arParamsAggregations as $paramsAggregation) {
            if (is_array($paramsAggregation)) {
                $field = $paramsAggregation[0];
                $func = $paramsAggregation[1];
                $key = $func;
                $code = $field . '_func_' . $func;
            } else {
                $key = 'terms';
                $code = $paramsAggregation;
                $field = $paramsAggregation;
            }

            if (is_array($paramsAggregation)) {
                $arAggregations[$code] = [
                    $key => [
                        'field' => $field
                    ]
                ];
            } else {
                $arAggregations[$code] = [
                    $key => [
                        'field' => $field,
                        'size' => 10000
                    ]
                ];
            }
        }
        return $arAggregations;
    }

    protected function isCheckBoxAggregation($aggregationResultItem)
    {
        return array_key_exists('buckets', $aggregationResultItem);
    }

    /**
     * @param Aggregations $aggregations
     * @param Filter|null $filter
     *
     * @return DisplayFilter
     */
    protected function getAggregationFilter(Aggregations $aggregations, ?Filter $filter): DisplayFilter
    {
        /**
         * @var Client $client
         */
        $client = $this->entity->getClient();

        // full
        $aggregationList = $this->getAggregationList($aggregations);
        $requestParams['index'] = $this->index;
        $requestParams['body']['aggregations'] = $this->getAggregations($aggregationList);
        $requestParams['body']['size'] = 0;
        $requestParams['body']['from'] = 1;

        $results = $client->search($requestParams);
        $aggregationResult = $results['aggregations'];

        $rawMatrix = $this->getEngineConvertedAggregations($aggregationResult);

        if ($filter !== null) {

            $rawFilter = clone $filter;

            // getFilterTerms
            $filterTerms = [];
            $selectedParams = $filter->getSelectParams();
            /**
             * @var FilterParam $selectedParam
             */
            foreach ($selectedParams as $selectedParam) {
                $termCode = $selectedParam->getCode();
                $term = [$selectedParam];
                $termFilter = new Filter();
                $termFilter->setRangeParams([]);
                $termFilter->setSelectParams($term);
                $filterTerms[$termCode] = $termFilter;
            }

            $rangedParams = $filter->getRangeParams();
            /**
             * @var FilterRangeParam $rangedParam
             */
            foreach ($rangedParams as $rangedParam) {

                $termCode = $rangedParam->getCode();
                $term = [$rangedParam];
                $termFilter = new Filter();
                $termFilter->setRangeParams($term);
                $termFilter->setSelectParams([]);
                $filterTerms[$termCode] = $termFilter;


                $rawFilterRangeParams = $rawFilter->getRangeParams();
                foreach ($rawFilterRangeParams as $index => $rawFilterRangeParam) {
                    $rawFilterRangeParamCode = $rawFilterRangeParam->getCode();
                    if($rawFilterRangeParamCode == $termCode) {
                        unset($rawFilterRangeParams[$index]);
                    }
                }

                $rawFilter->setRangeParams($rawFilterRangeParams);
                if(empty($rawFilter->getRangeParams()) && empty($rawFilter->getSelectParams())) {
                    continue;
                }
                $filterTerms[$this->rangePropAggsPrefix . $termCode] = $rawFilter;

            }

            // getTermMatrix
            /**
             *
             * @var Client $client
             */
            $client = $this->entity->getClient();
            $termMatrix = [];

            $futures = [];
            foreach ($filterTerms as $filterTermCode => $filterTerm) {
                $termQuery = $this->getQuery(null, $filterTerm);
                $aggregationList = $this->getAggregationList($aggregations);
                $requestParams['index'] = $this->index;
                if (!empty($termQuery)) {
                    $requestParams['body']['query'] = $termQuery;
                }
                $requestParams['body']['aggregations'] = $this->getAggregations($aggregationList);
                $requestParams['body']['size'] = 0;
                $requestParams['body']['from'] = 1;
                $requestParams['client'] = [
                    'future' => 'lazy'
                ];
                // future mode
                $futures[$filterTermCode] = $client->search($requestParams);
            }

            foreach ($futures as $filterTermCode => $future) {
                $aggregationResult = $future['aggregations'];
                $filterData = $this->getEngineConvertedAggregations($aggregationResult);
                $termMatrix[$filterTermCode] = $filterData;
            }
            unset($futures);


            foreach ($termMatrix as $term => $termMatrixItem) {

                foreach ($termMatrixItem['range_params'] as $termMatrixItemRangeParamCode => $termMatrixItemRangeParamValue) {

                    if ($termMatrixItemRangeParamCode == $term) {
                        continue;
                    }

                    if ($rawMatrix['range_params'][$termMatrixItemRangeParamCode]['min_displayed'] < $termMatrixItemRangeParamValue['min_total']) {

                        $rawMatrix['range_params'][$termMatrixItemRangeParamCode]['min_displayed'] = $termMatrixItemRangeParamValue['min_total'];
                    }

                    if ($rawMatrix['range_params'][$termMatrixItemRangeParamCode]['max_displayed'] > $termMatrixItemRangeParamValue['max_total']) {

                        $rawMatrix['range_params'][$termMatrixItemRangeParamCode]['max_displayed'] = $termMatrixItemRangeParamValue['max_total'];
                    }
                }


                if(stripos($term, $this->rangePropAggsPrefix) !== false) {
                    continue;
                }

                foreach ($termMatrixItem['select_params'] as $selectParamCode => $selectParam) {


                    if ($selectParamCode == $term) {
                        continue;
                    }

                    // select params
                    $diffPropValues = array_diff_key($rawMatrix['select_params'][$selectParamCode]['values'], $selectParam['values']);
                    $intersectPropValues = array_intersect_key($selectParam['values'], $rawMatrix['select_params'][$selectParamCode]['values']);

                    foreach ($diffPropValues as $difPropValueCode => $diffPropValue) {
                        $rawMatrix['select_params'][$selectParamCode]['values'][$difPropValueCode]['count'] = 0;
                        $rawMatrix['select_params'][$selectParamCode]['values'][$difPropValueCode]['disabled'] = true;
                    }
                    foreach ($intersectPropValues as $intersectPropValueCode => $intersectPropValue) {
                        $rawMatrix['select_params'][$selectParamCode]['values'][$intersectPropValueCode]['disabled'] = false;
                        if ($rawMatrix['select_params'][$selectParamCode]['values'][$intersectPropValueCode]['count'] > $intersectPropValue['count']) {
                            $rawMatrix['select_params'][$selectParamCode]['values'][$intersectPropValueCode]['count'] = $intersectPropValue['count'];
                        }
                    }
                }
            }
        }

        $resultMatrix = $rawMatrix;

        if ($filter !== null) {
            // select
            $filterSelectedParams = $filter->getSelectParams();
            foreach ($filterSelectedParams as $filterSelectedParam) {
                $code = $filterSelectedParam->getCode();
                $values = $filterSelectedParam->getValues();
                foreach ($values as $value) {
                    $val = $value->getValue();
                    $resultMatrix['select_params'][$code]['values'][$val]['selected'] = true;
                }
            }

            // range select
            $filterRangedParams = $filter->getRangeParams();
            foreach ($filterRangedParams as $filterRangedParam) {
                $code = $filterRangedParam->getCode();
                $filterRangedParamMinSelected = $filterRangedParam->getMinValue();
                $filterRangedParamMaxSelected = $filterRangedParam->getMaxValue();
                $resultMatrix['range_params'][$code]['min_selected'] = $filterRangedParamMinSelected;
                $resultMatrix['range_params'][$code]['max_selected'] = $filterRangedParamMaxSelected;

            }

            // range display
            $filterRangedParams = $filter->getRangeParams();
            foreach ($filterRangedParams as $filterRangedParam) {
                $code = $filterRangedParam->getCode();
                $filterRangedParamMinSelected = $filterRangedParam->getMinValue();
                $filterRangedParamMaxSelected = $filterRangedParam->getMaxValue();


                if($resultMatrix['range_params'][$code]['min_displayed'] < $filterRangedParamMinSelected) {
                    $resultMatrix['range_params'][$code]['min_displayed'] = $filterRangedParamMinSelected;
                }

                if($resultMatrix['range_params'][$code]['max_displayed'] > $filterRangedParamMaxSelected) {
                    $resultMatrix['range_params'][$code]['max_displayed'] = $filterRangedParamMaxSelected;
                }
            }
        }

        // array values
        $resultMatrix['select_params'] = array_values($resultMatrix['select_params']);
        foreach ($resultMatrix['select_params'] as $index => $selectParam) {
            $resultMatrix['select_params'][$index]['values'] = array_values($selectParam['values']);
        }

        $outputFilter = new DisplayFilter($resultMatrix);
        return $outputFilter;
    }

    protected function getAggregationList(Aggregations $aggregations)
    {
        $aggregationList = [];
        /**
         * @var Aggregation $aggregation
         */
        foreach ($aggregations->getItems() as $aggregation) {
            switch ($aggregation->getType()) {
                case Aggregation::TYPE_CHECKBOX:
                    {
                        $aggregationList[] = $aggregation->getField();
                        break;
                    }
                case Aggregation::TYPE_RANGE:
                    {
                        $aggregationList[] = [
                            $aggregation->getField(),
                            'min'
                        ];
                        $aggregationList[] = [
                            $aggregation->getField(),
                            'max'
                        ];
                        break;
                    }
            }
        }
        return $aggregationList;
    }

    protected function getEngineConvertedAggregations($aggregationResult)
    {
        $filterData = [
            'range_params' => [],
            'select_params' => [],
        ];
        foreach ($aggregationResult as $field => $aggregationResultItem) {
            if ($this->isCheckBoxAggregation($aggregationResultItem)) {
                $filterData['select_params'][$field] = [
                    'code' => $field
                ];
                $filterData['select_params'][$field]['values'] = [];
                foreach ($aggregationResultItem['buckets'] as $bucket) {
                    $filterData['select_params'][$field]['values'][$bucket['key']] = [
                        'value' => $bucket['key'],
                        'count' => $bucket['doc_count'],
                        'selected' => false,
                        'disabled' => false
                    ];
                }
            } else {
                $fieldRangeData = explode('_func_', $field);
                $fieldCode = reset($fieldRangeData);
                $func = end($fieldRangeData);

                if (!array_key_exists($fieldCode, $filterData['range_params'])) {
                    $filterData['range_params'][$fieldCode] = [
                        'code' => $fieldCode
                    ];
                }
                switch ($func) {
                    case 'min':
                        {
                            $filterData['range_params'][$fieldCode]['min_total'] = $aggregationResultItem['value'];
                            $filterData['range_params'][$fieldCode]['min_selected'] = $aggregationResultItem['value'];
                            $filterData['range_params'][$fieldCode]['min_displayed'] = $aggregationResultItem['value'];
                            break;
                        }

                    case 'max':
                        {
                            $filterData['range_params'][$fieldCode]['max_total'] = $aggregationResultItem['value'];
                            $filterData['range_params'][$fieldCode]['max_selected'] = $aggregationResultItem['value'];
                            $filterData['range_params'][$fieldCode]['max_displayed'] = $aggregationResultItem['value'];
                            break;
                        }
                }
            }
        }
        return $filterData;
    }
}