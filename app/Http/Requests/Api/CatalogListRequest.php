<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\FilterParam;
use SwaggerUnAuth\Model\ModelInterface;
use SwaggerUnAuth\ObjectSerializer;

class CatalogListRequest extends Request
{
    public function rules()
    {
        return [
            'engine' => 'in:elasticsearch'
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
        $filter = ObjectSerializer::deserialize($this->getDeserializeData(), Filter::class, null);
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

    protected function validateEngine()
    {
        $engine = $this->get('engine');
        print_r($engine);
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
        });
    }




}