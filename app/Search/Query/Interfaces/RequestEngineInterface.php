<?php
namespace App\Search\Query\Interfaces;

use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\ListItem;
use SwaggerUnAuth\Model\ListItems;

interface RequestEngineInterface
{
    /**
     * @param Filter $filter
     * @return ListItems
     */
    public function postCatalogList(Filter $filter = null) : ListItems;
}