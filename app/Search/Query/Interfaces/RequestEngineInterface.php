<?php
namespace App\Search\Query\Interfaces;

use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\ListItem;

interface RequestEngineInterface
{
    /**
     * @param Filter $filter
     * @return ListItem[]
     */
    public function postCatalogList(Filter $filter);
}