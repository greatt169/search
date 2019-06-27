<?php

namespace App\Search\Query\Request;

use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\ListItem;

class Elasticsearch extends Engine
{
    /**
     * @param Filter $filter
     * @return ListItem[]
     */
    public function postCatalogList(Filter $filter)
    {
        return [new ListItem()];
    }
}