<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiException;
use App\Helpers\Interfaces\SerializerInterface;
use App\Search\UseCases\Errors\Error;
use Illuminate\Foundation\Http\FormRequest;
use SwaggerSearch\Model\Engine;
use SwaggerSearch\Model\ModelInterface;
use SwaggerSearch\ObjectSerializer;

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

    protected $engine = null;
    private $validationErrorMessageTemplate = 'key %s doesn\'t exist in valid data';

    public function setValid($key, $value) {
        $this->validData[$key] = $value;
    }

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null, SerializerInterface $serializer = null)
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
            throw new ApiException(sprintf($this->validationErrorMessageTemplate, $key), Error::CODE_INTERNAL_SERVER_ERROR);
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
            throw new ApiException($debugMessage, Error::CODE_BAD_REQUEST);
        }
    }
}