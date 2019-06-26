<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
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
            if (0) {

                /**
                 * @var \Illuminate\Validation\Validator  $validator
                 */
                throw new ApiException('Bad Request', 'Валидация не пройдена', 400);
            }
        });
    }


}