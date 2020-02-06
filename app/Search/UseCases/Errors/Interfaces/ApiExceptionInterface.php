<?php

namespace App\Search\UseCases\Errors\Interfaces;

use SwaggerSearch\Model\ModelInterface;

interface ApiExceptionInterface
{
    /**
     * @return array
     */
    public function getInvalidProperties(): array;

    /**
     * @return ModelInterface
     */
    public function getModel(): ModelInterface;

    /**
     * @return string
     */
    public function getAppCode(): string;
}