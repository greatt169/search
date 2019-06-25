<?php

namespace App\Search\Query\Request;

use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\ListItem;

abstract class Base
{
    /**
     * @param Filter $filter
     * @return ListItem[]
     */
    public abstract function getList(Filter $filter);
}