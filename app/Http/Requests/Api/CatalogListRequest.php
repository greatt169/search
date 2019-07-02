<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use SwaggerUnAuth\Model\Engine;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\FilterParam;
use SwaggerUnAuth\Model\ModelInterface;
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
            $data = $this->getDeserializeData()->engine;
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
        $filter = ObjectSerializer::deserialize($this->getDeserializeData()->filter, Filter::class, null);
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
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateFilter();
            /**
             * @var \Illuminate\Validation\Validator  $validator
             */
            $errors = $validator->errors();
            if($errors->count() > 0) {
                throw new ApiException('BadRequest', $errors->first(), 400);
            } else {
                $this->setValid('engine', $this->getEngine());
            }
        });
    }
}