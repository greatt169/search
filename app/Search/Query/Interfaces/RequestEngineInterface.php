<?php
namespace App\Search\Query\Interfaces;

use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\ListItems;
use SwaggerUnAuth\Model\SelectedFields;
use SwaggerUnAuth\Model\Sorts;

interface RequestEngineInterface
{
    /**
     * @param Filter|null $filter
     * @param Sorts|null $sorts
     * @param SelectedFields|null $selectedFields
     * @param int $page
     * @param int $pageSize
     * @return ListItems
     */
    public function postCatalogList(Filter $filter = null, Sorts $sorts = null, SelectedFields $selectedFields = null, $page = 1, $pageSize = 20) : ListItems;

    /**
     * @param Sorts $sorts
     * @return array
     */
    public function getEngineConvertedSorts(Sorts $sorts): array;

    /**
     * @param Filter $filter
     * @return array
     */
    public function getEngineConvertedFilter(Filter $filter): array;

    /**
     * @param SelectedFields $selectedFields
     * @return array
     */
    public function getEngineConvertedSelectedFields(SelectedFields $selectedFields): array;
}