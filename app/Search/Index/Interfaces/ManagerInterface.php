<?php

namespace App\Search\Index\Interfaces;

use App\Exceptions\ApiException;
use App\Search\Entity\Interfaces\EntityInterface;

interface ManagerInterface
{
    public function createIndex();

    public function dropIndex();

    public function setMapping();

    public function indexAll();

    public function __construct(SourceInterface $source, EntityInterface $entity);

    /**
     * @throws ApiException
     */
    public function reindex();

    /**
     * @throws ApiException
     */
    public function update();

    /**
     * @return SourceInterface
     */
    public function getSource();
}