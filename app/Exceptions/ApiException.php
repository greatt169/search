<?php

namespace App\Exceptions;

use App\Search\UseCases\Errors\Error;
use App\Search\UseCases\Errors\Interfaces\ApiExceptionInterface;
use SwaggerSearch\Model\ModelInterface;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @var Error $errorService;
     */
    protected $errorService;

    public function __construct(string $message, $appCode, ModelInterface $model = null, Error $errorService = null)
    {
        parent::__construct();
        if ($model !== null) {
            $this->invalidProperties = $model->listInvalidProperties();
            $this->model = $model->getModelName();
        }
        $this->message = $message;
        $this->appCode = $appCode;
        $this->errorService = $errorService;
        if($errorService === null) {
            $this->errorService = new Error();
        }
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

    public function render($request)
    {
        $error = $this->errorService->getError($this);
        return new Response($error->__toString(), $error->getHttpCode());
    }
}