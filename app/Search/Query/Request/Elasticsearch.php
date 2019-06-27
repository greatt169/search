<?php

namespace App\Search\Query\Request;

use App\Search\Entity\Interfaces\EntityInterface;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\ListItem;

class Elasticsearch extends Engine
{
    /**
     * @var EntityInterface
     */
    protected $entity;

    public function __construct($engine, EntityInterface $entity)
    {
        parent::__construct($engine);
        $this->entity = $entity;
    }

    /**
     * @param Filter $filter
     * @return ListItem[]
     */
    public function postCatalogList(Filter $filter)
    {
        return [new ListItem()];
    }
}