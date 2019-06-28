<?php
namespace App\Search\Query\Interfaces;

use SwaggerUnAuth\Model\InputFilter;
use SwaggerUnAuth\Model\ListItem;

interface RequestEngineInterface
{
    /**
     * @param InputFilter $filter
     * @return ListItem[]
     */
    public function postCatalogList(InputFilter $filter);
}