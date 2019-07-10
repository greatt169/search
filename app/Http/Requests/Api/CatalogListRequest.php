<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use Illuminate\Validation\Validator as ValidatorAlias;
use SwaggerSearch\Model\Engine;
use SwaggerSearch\Model\Filter;
use SwaggerSearch\Model\FilterParam;
use SwaggerSearch\Model\ModelInterface;
use SwaggerSearch\Model\SelectedFields;
use SwaggerSearch\Model\Sort;
use SwaggerSearch\Model\Sorts;
use SwaggerSearch\ObjectSerializer;

class CatalogListRequest extends Request
{
    protected $engine = null;

    /**
     * @param bool $withData
     * @return Engine
     */
    protected function getEngine($withData = true)
    {
        $data = '';
        if ($withData) {
            $sourceData = $this->getDeserializeData();
            if (property_exists($sourceData, 'engine')) {
                $data = $sourceData->engine;
            }
        }
        /**
         * @var Engine $engine
         */
        $engine = ObjectSerializer::deserialize($data, Engine::class, null);
        $this->engine = $engine;
        return $engine;
    }

    public function rules()
    {
        $allowableValues = implode(',', $this->getEngine(false)->getNameAllowableValues());
        return [
            'engine.name' => 'in:' . $allowableValues
        ];
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
    protected function validateSelectedFields()
    {
        $sourceData = $this->getDeserializeData();
        if (property_exists($sourceData, 'selectedFields')) {
            $selectedFieldsData = $sourceData->selectedFields;
        } else {
            $this->setValid('selectedFields', null);
            return;
        }
        /**
         * @var SelectedFields $selectedFields
         */
        $selectedFields = ObjectSerializer::deserialize($selectedFieldsData, SelectedFields::class, null);
        $this->validateBySwaggerModel($selectedFields);
        $this->setValid('selectedFields', $selectedFields);
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
            $this->validateFilter();
            $this->validateSort();
            $this->validateSelectedFields();
            /**
             * @var ValidatorAlias $validator
             */
            $errors = $validator->errors();
            if ($errors->count() > 0) {
                throw new ApiException('BadRequest', $errors->first(), 400);
            } else {
                $this->setValid('engine', $this->getEngine());
            }
        });
    }
}