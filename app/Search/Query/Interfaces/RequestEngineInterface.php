<?php
namespace App\Search\Query\Interfaces;

use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\ListItem;
use SwaggerUnAuth\Model\ListItems;
use SwaggerUnAuth\Model\SelectedFields;
use SwaggerUnAuth\Model\Sort;

interface RequestEngineInterface
{
    /**
     * @param Filter|null $filter
     * @param Sort|null $sort
     * @param SelectedFields|null $selectedFields
     * @return ListItems
     */
    public function postCatalogList(Filter $filter = null, Sort $sort = null, SelectedFields $selectedFields = null) : ListItems;
}