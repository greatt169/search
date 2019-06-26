<?php

namespace App\Http\Requests\Api;

use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\ObjectSerializer;

class CatalogListRequest extends Request
{

    public function rules()
    {
        return [

        ];
    }

    public function isValid()
    {
        //$filter = ObjectSerializer::deserialize($this, Filter::class, null);
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $filter = ObjectSerializer::deserialize($this, Filter::class, null);
        $this->setValid('filter', $filter);

        $validator->after(function ($validator) {
            if (1) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }


}