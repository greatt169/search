<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use Exception;
use Illuminate\Validation\Validator as ValidatorAlias;
use SwaggerSearch\Model\ReindexParams;
use SwaggerSearch\ObjectSerializer;

class ReindexRequest extends Request
{
    protected $params = null;

    public function rules()
    {
        return [
            'dataLink' => 'required|min:5',
        ];
    }

    /**
     * @return ReindexParams
     */
    protected function getParams()
    {
        if($this->params !== null) {
            return $this->params;
        }

        $data = $this->getDeserializeData();
        /**
         * @var ReindexParams $params
         */
        $params = ObjectSerializer::deserialize($data, ReindexParams::class, null);
        $this->params = $params;
        return $params;
    }

    /**
     * @param $code
     * @return null
     */
    protected function getParam($code)
    {
        $params = $this->getParams();
        if($params !== null) {
            $getters = $params::getters();
            $attribute = array_search($code,$params::attributeMap());
            $getter = $getters[$attribute];
            $param = $params->$getter();
            return $param;
        }
        return null;
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
                } else {
                    $this->setValid('dataLink', $this->getParam('dataLink'));
                }
            } catch (Exception $exception) {
                throw new ApiException('Internal Server Error', $exception->getMessage() , 500);
            }
        });

    }
}