<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CatalogListRequest;
use Illuminate\Http\Request;
use App\Search\Helpers\ApiError;
use App\Search\Query\Request\Elasticsearch;
use Exception;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\ObjectSerializer;

class SearchController extends Controller
{
    public function catalogList(CatalogListRequest $request)
    {
        /**
         * @var Filter $filter
         */
        $filter = $request->getValid('filter');
        if(!$filter->valid()) {
            return ApiError::returnError('Bad Request', $filter->listInvalidProperties(), 400);
        }
        try {
            switch ($request->get('engine')) {
                case 'elasticsearch': { // TODO: ИИ
                    $elasticSearch = new Elasticsearch();
                    $items = $elasticSearch->postCatalogList($filter);
                    break;
                }
                default: {
                    return ApiError::returnError(sprintf('engine %s not found', $request->get('engine')), '', 404);
                }
            }
        } catch (Exception $e) {
            return ApiError::returnError('Internal Server Error', $e->getMessage(), 500);
        }
        return $items;
    }
}