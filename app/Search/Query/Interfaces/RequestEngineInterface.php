<?php
namespace App\Search\Query\Interfaces;

use SwaggerSearch\Model\Aggregations;
use SwaggerSearch\Model\Filter;
use SwaggerSearch\Model\ListItems;
use SwaggerSearch\Model\ReindexResponse;
use SwaggerSearch\Model\Search;
use SwaggerSearch\Model\Sorts;

interface RequestEngineInterface
{
    /**
     * @param Search|null $search
     * @param Filter|null $filter
     * @param Aggregations|null $aggregations
     * @param Sorts|null $sorts
     * @param int $page
     * @param int $pageSize
     * @return ListItems
     */
    public function postCatalogList(Search $search = null, Filter $filter = null, Aggregations $aggregations = null, Sorts $sorts = null, $page = 1, $pageSize = 20) : ListItems;

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
     * @param Search $search
     * @return array
     */
    public function getEngineConvertedSearch(Search $search): array;

    /**
     * @param $index
     * @param $dataLink
     * @param $settingsLink
     * @return mixed
     */
    public function reindex(string $index, string $dataLink, $settingsLink): ReindexResponse;

    /**
     * @param $index
     * @param $dataLink
     * @return mixed
     */
    public function update(string $index, string $dataLink) : ReindexResponse;

    /**
     * @param string $id
     * @return mixed
     */
    public function deleteElement(string $id);
}