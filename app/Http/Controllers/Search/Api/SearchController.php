<?php

namespace App\Http\Controllers\Search\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CatalogListRequest;
use App\Search\Query\Interfaces\RequestEngineInterface;
use App\Search\Query\Request\Engine as RequestEngine;
use App\Search\UseCases\Errors\Error;
use SwaggerSearch\Model\Aggregations;
use SwaggerSearch\Model\Filter;
use SwaggerSearch\Model\Search;
use SwaggerSearch\Model\Sorts;
use SwaggerSearch\Model\ListItems;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SearchController extends Controller
{
    /**
     * @param CatalogListRequest $request
     * @param Error $errorService
     * @return ListItems|Response
     */
    public function catalogList(CatalogListRequest $request, Error $errorService)
    {
        try {
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
             * @var Aggregations $aggregations
             */
            $aggregations = $request->getValid('aggregations');

            $engine = $request->route('engine');
            $index = $request->route('index');
            $page = $request->get('page');
            $pageSize = $request->get('pageSize');

            $items = null;
            /**
             * @var RequestEngineInterface $engineRequest
             */
            $engineRequest = RequestEngine::getInstance($engine, $index);
            $items = $engineRequest->postCatalogList($search, $filter, $aggregations, $sorts, $page, $pageSize);
            return $items;
        } catch (Throwable $exception) {
            $error = $errorService->getError($exception);
            return new Response($error->__toString(), $error->getHttpCode());
        }
    }
}