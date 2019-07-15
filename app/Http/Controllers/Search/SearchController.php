<?php

namespace App\Http\Controllers\Search;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CatalogListRequest;
use App\Search\Query\Interfaces\RequestEngineInterface;
use SwaggerSearch\Model\Engine;
use App\Search\Query\Request\Engine as RequestEngine;
use SwaggerSearch\Model\Filter;
use SwaggerSearch\Model\Search;
use SwaggerSearch\Model\Sorts;

class SearchController extends Controller
{
    /**
     * @param CatalogListRequest $request
     * @return \SwaggerSearch\Model\ListItems
     * @throws ApiException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function catalogList(CatalogListRequest $request)
    {
        /**
         * @var Search $search
         */
        $search = $request->getValid('search');

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

        $items = null;
        /**
         * @var RequestEngineInterface $engineRequest
         */
        $engineRequest = RequestEngine::getInstance($engine->getName(), $index);
        $items = $engineRequest->postCatalogList($search, $filter, $sorts, $page, $pageSize);
        return $items;
    }
}