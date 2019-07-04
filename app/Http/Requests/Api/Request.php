<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use App\Helpers\Interfaces\SerializerInterface;
use Illuminate\Foundation\Http\FormRequest;
use SwaggerUnAuth\Model\ModelInterface;

class Request extends FormRequest
{
    /**
     * @var array
     */
    protected $validData;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    protected $data = null;

    public function setValid($key, $value) {
        $this->validData[$key] = $value;
    }

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null, SerializerInterface $serializer)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->serializer = $serializer;
    }

    /**
     * @param $key
     * @return mixed
     * @throws ApiException
     */
    public function getValid($key) {
        if(!array_key_exists($key, $this->validData)) {
            throw new ApiException('Internal Server Error', sprintf('key %s doesn\'t exist in valid data', $key), 500);
        }
        return $this->validData[$key];
    }

    protected function getDeserializeData()
    {
        if($this->data === null) {
            $data = $this->serializer::__toArray($this->input());
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