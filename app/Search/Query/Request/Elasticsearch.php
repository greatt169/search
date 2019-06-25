<?php

namespace App\Search\Query\Request;

use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\ListItem;

class Elasticsearch extends Base
{
    /**
     * @param Filter $filter
     * @return ListItem[]
     */
    public function getList(Filter $filter)
    {
        return [new ListItem()];
    }
}