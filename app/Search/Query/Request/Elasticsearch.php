<?php

namespace App\Search\Query\Request;

use SwaggerUnAuth\Model\CatalogListFilter;
use SwaggerUnAuth\Model\ListItem;

class Elasticsearch extends Base
{
    /**
     * @param CatalogListFilter $filter
     * @return ListItem[]
     */
    public function postCatalogList(CatalogListFilter $filter)
    {
        return [new ListItem()];
    }
}