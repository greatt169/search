<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use App\Search\UseCases\Errors\Error;
use Exception;
use Illuminate\Validation\Validator as ValidatorAlias;
use SwaggerSearch\Model\Aggregation;
use SwaggerSearch\Model\Aggregations;
use SwaggerSearch\Model\EngineParam;
use SwaggerSearch\Model\Filter;
use SwaggerSearch\Model\FilterParam;
use SwaggerSearch\Model\ModelInterface;
use SwaggerSearch\Model\Search;
use SwaggerSearch\Model\Sort;
use SwaggerSearch\Model\Sorts;
use SwaggerSearch\ObjectSerializer;

class CatalogListRequest extends Request
{
    /**
     * @return Search
     */
    protected function getSearch()
    {
        $sourceData = $this->getDeserializeData();
        if (property_exists($sourceData, 'search')) {
            $data = $sourceData->search;
        } else {
            return null;
        }
        /**
         * @var Search $search
         */
        $search = ObjectSerializer::deserialize($data, Search::class, null);
        return $search;
    }

    public function rules()
    {
        return [
            'search.query' => 'max:255|min:3',
            'engine' => 'in:' . implode(',', EngineParam::getAllowableEnumValues())
        ];
    }

    protected function validationData()
    {
        return array_merge((array) parent::all(), (array) $this->route()->parameters());
    }

    /**
     * @throws ApiException
     */
    protected function validateFilter()
    {
        /**
         * @var Filter $filter
         */
        $sourceData = $this->getDeserializeData();
        if (property_exists($sourceData, 'filter')) {
            $filterData = $sourceData->filter;
        } else {
            $this->setValid('filter', null);
            return;
        }
        $filter = ObjectSerializer::deserialize($filterData, Filter::class, null);
        $this->validateBySwaggerModel($filter);
        $filterRangeParams = $filter->getRangeParams();

        /**
         * @var ModelInterface $filterRangeParam
         */
        foreach ($filterRangeParams as $filterRangeParam) {
            $this->validateBySwaggerModel($filterRangeParam);
        }
        $filterSelectParams = $filter->getSelectParams();

        /**
         * @var FilterParam $filterSelectParam
         */
        foreach ($filterSelectParams as $filterSelectParam) {
            $this->validateBySwaggerModel($filterSelectParam);
            $filterSelectParamValues = $filterSelectParam->getValues();
            /**
             * @var ModelInterface $filterSelectParamValue
             */
            foreach ($filterSelectParamValues as $filterSelectParamValue) {
                $this->validateBySwaggerModel($filterSelectParamValue);
            }
        }
        $this->setValid('filter', $filter);
    }

    /**
     * @throws ApiException
     */
    protected function validateSort()
    {
        /**
         * @var Sorts $sorts
         * @var Sort $sort
         */
        $sourceData = $this->getDeserializeData();
        if (property_exists($sourceData, 'sorts')) {
            $sortData = $sourceData->sorts;
        } else {
            $this->setValid('sorts', null);
            return;
        }
        $sorts = ObjectSerializer::deserialize($sortData, Sorts::class, null);
        $this->validateBySwaggerModel($sorts);
        $sortItems = $sorts->getItems();

        foreach ($sortItems as $sort) {
            $this->validateBySwaggerModel($sort);
        }
        $this->setValid('sorts', $sorts);
    }

    /**
     * @throws ApiException
     */
    protected function validateAggregations()
    {
        /**
         * @var Aggregations $aggregations
         * @var Aggregation $aggregation
         */
        $sourceData = $this->getDeserializeData();
        if (property_exists($sourceData, 'aggregations')) {
            $sortData = $sourceData->aggregations;
        } else {
            $this->setValid('aggregations', null);
            return;
        }
        $aggregations = ObjectSerializer::deserialize($sortData, Aggregations::class, null);
        $this->validateBySwaggerModel($aggregations);
        $aggregationItems = $aggregations->getItems();

        foreach ($aggregationItems as $aggregation) {
            $this->validateBySwaggerModel($aggregation);
        }
        $this->setValid('aggregations', $aggregations);
    }

    /**
     * Configure the validator instance.
     *
     * @param ValidatorAlias $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            try {
                $this->validateFilter();
                $this->validateSort();
                $this->validateAggregations();
                /**
                 * @var ValidatorAlias $validator
                 */
                $errors = $validator->errors();
                if ($errors->count() > 0) {
                    throw new ApiException($errors->first(), Error::CODE_BAD_REQUEST);
                } else {
                    $this->setValid('search', $this->getSearch());
                }
            } catch (Exception $exception) {
                throw new ApiException($exception->getMessage(), Error::CODE_INTERNAL_SERVER_ERROR);
            }
        });
    }
}