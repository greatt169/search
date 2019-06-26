<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Search\Helpers\ApiError;
use App\Search\Query\Request\Elasticsearch;
use Exception;
use Illuminate\Http\Request;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\ObjectSerializer;

class SearchController extends Controller
{
    public function catalogList(Request $request)
    {
        $requestParam = json_decode(json_encode($request->all()));
        $engine = $request->get('engine');
        $index = $request->get('index');
        /**
         * @var Filter $filter
         */
        $filter = ObjectSerializer::deserialize($requestParam, Filter::class, null);
        if(!$filter->valid()) {
            return ApiError::returnError('Bad Request', $filter->listInvalidProperties(), 400);
        }
        try {
            switch ($engine) {
                case 'elasticsearch': { // TODO: ИИ
                    $elasticSearch = new Elasticsearch();
                    $items = $elasticSearch->postCatalogList($filter);
                    break;
                }
                default: {
                    return ApiError::returnError(sprintf('engine %s not found', $engine), '', 404);
                }
            }
        } catch (Exception $e) {
            return ApiError::returnError(sprintf('engine %s not found', $engine), '', 500);
        }
        return $items;
    }
}