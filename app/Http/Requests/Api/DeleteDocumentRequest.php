<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use Exception;
use Illuminate\Validation\Validator as ValidatorAlias;
use SwaggerSearch\Model\EngineParam;

class DeleteDocumentRequest extends Request
{
    public function rules()
    {
        return [
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
                    throw new ApiException('BadRequest', $errors->first(), 400);
                }
            } catch (Exception $exception) {
                throw new ApiException('Internal Server Error', $exception->getMessage() , 500);
            }
        });

    }

}