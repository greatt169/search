<?php

namespace App\Search\Query\Request;

use SwaggerUnAuth\Model\CatalogListFilter;
use SwaggerUnAuth\Model\ListItem;

abstract class Base
{
    /**
     * @param CatalogListFilter $filter
     * @return ListItem[]
     */
    public abstract function postCatalogList(CatalogListFilter $filter);
}