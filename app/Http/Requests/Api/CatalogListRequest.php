<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use Exception;
use SwaggerUnAuth\Model\Engine;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\FilterParam;
use SwaggerUnAuth\Model\ModelInterface;
use SwaggerUnAuth\Model\Sort;
use SwaggerUnAuth\ObjectSerializer;

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
        if($withData) {
            $sourceData = $this->getDeserializeData();
            if(property_exists($sourceData, 'engine')) {
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
        if(property_exists($sourceData, 'filter')) {
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
    private function validateSort()
    {
        /**
         * @var Sort $sort
         */
        $sourceData = $this->getDeserializeData();
        if(property_exists($sourceData, 'sort')) {
            $sortData = $sourceData->sort;
        } else {
            $this->setValid('sort', null);
            return;
        }
        $sort = ObjectSerializer::deserialize($sortData, Sort::class, null);

        $this->validateBySwaggerModel($sort);
        $this->setValid('sort', $sort);
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            try {
                $this->validateFilter();
                $this->validateSort();
                /**
                 * @var \Illuminate\Validation\Validator  $validator
                 */
                $errors = $validator->errors();
                if($errors->count() > 0) {
                    throw new ApiException('BadRequest', $errors->first(), 400);
                } else {
                    $this->setValid('engine', $this->getEngine());
                }
            } catch (Exception $exception) {
                throw new ApiException('BadRequest', $exception->getMessage(), 400);
            }
        });
    }
}