<?php

namespace App\Http\Controllers\Search;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CatalogListRequest;
use App\Search\Query\Interfaces\RequestEngineInterface;
use SwaggerUnAuth\Model\Engine;
use App\Search\Query\Request\Engine as RequestEngine;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\Model\Sorts;

class SearchController extends Controller
{
    /**
     * @param CatalogListRequest $request
     * @return \SwaggerUnAuth\Model\ListItems
     * @throws ApiException
     */
    public function catalogList(CatalogListRequest $request)
    {
        /**
         * @var Filter $filter
         */
        $filter = $request->getValid('filter');

        /**
         * @var Sorts $sorts
         */
        $sorts = $request->getValid('sorts');

        /**
         * @var Engine $engine
         */
        $engine = $request->getValid('engine');
        $index = $request->get('index');
        $page = $request->get('page');
        $pageSize = $request->get('pageSize');
        $selectedFields = $request->getValid('selectedFields');

        $items = null;
        /**
         * @var RequestEngineInterface $engineRequest
         */
        $engineRequest = RequestEngine::getInstance($engine->getName(), $index);
        $items = $engineRequest->postCatalogList($filter, $sorts, $selectedFields, $page, $pageSize);
        return $items;
    }
}