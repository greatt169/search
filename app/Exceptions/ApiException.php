<?php

namespace App\Exceptions;

use App\Search\UseCases\Errors\Interfaces\ApiExceptionInterface;
use SwaggerSearch\Model\ModelInterface;

class ApiException extends \Exception implements ApiExceptionInterface
{

    /**
     * @var ModelInterface
     */
    protected $model;

    protected $invalidProperties;
    /**
     * @var string
     */
    private $appCode;
    /**
     * @var string
     */
    protected $message;

    public function __construct(string $message, $appCode, ModelInterface $model = null)
    {
        parent::__construct();
        if ($model !== null) {
            $this->invalidProperties = $model->listInvalidProperties();
            $this->model = $model->getModelName();
        }
        $this->message = $message;
        $this->appCode = $appCode;
    }

    /**
     * @return array
     */
    public function getInvalidProperties(): array
    {
        return $this->invalidProperties;
    }


    public function getModel(): ModelInterface
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getAppCode(): string
    {
        return $this->appCode;
    }
}