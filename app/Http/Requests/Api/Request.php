<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use Illuminate\Foundation\Http\FormRequest;
use SwaggerUnAuth\Model\ModelInterface;

class Request extends FormRequest
{
    /**
     * @var array
     */
    protected $validData;

    protected $data = null;

    public function setValid($key, $value) {
        $this->validData[$key] = $value;
    }

    public function getValid($key) {
        return $this->validData[$key];
    }

    protected function getDeserializeData()
    {
        if($this->data === null) {
            $data = json_decode(json_encode($this->input()));
            $this->data = $data;
            return $data;
        }
        return $this->data;
    }

    /**
     * @param ModelInterface $object
     * @throws ApiException
     */
    protected function validateBySwaggerModel(ModelInterface $object)
    {
        if(!$object->valid()) {
            $firstError = $object->listInvalidProperties()[0];
            $debugMessage = 'field of model ' . get_class($object) . ': ' . $firstError;
            throw new ApiException('Bad Request', $debugMessage , 400);
        }
    }
}