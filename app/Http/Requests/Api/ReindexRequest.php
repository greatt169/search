<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use App\Search\UseCases\Errors\Error;
use Exception;
use Illuminate\Validation\Validator as ValidatorAlias;
use SwaggerSearch\Model\EngineParam;

class ReindexRequest extends Request
{
    protected $params = null;

    public function rules()
    {
        return [
            'dataLink' => 'required|min:5',
            'engine' => 'in:' . implode(',', EngineParam::getAllowableEnumValues())
        ];
    }

    protected function validationData()
    {
        return array_merge((array) parent::all(), (array) $this->route()->parameters());
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
                /**
                 * @var ValidatorAlias $validator
                 */
                $errors = $validator->errors();
                if ($errors->count() > 0) {
                    throw new ApiException($errors->first(), Error::CODE_BAD_REQUEST);
                }
            } catch (Exception $exception) {
                throw new ApiException($exception->getMessage(), Error::CODE_INTERNAL_SERVER_ERROR);
            }
        });

    }
}