<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Search\Query\Request\Elasticsearch;
use Exception;
use Illuminate\Http\Request;
use SwaggerUnAuth\Model\Error;
use SwaggerUnAuth\Model\Filter;
use SwaggerUnAuth\ObjectSerializer;

class SearchController extends Controller
{
    public function catalogList($version, $engine, $index, Request $request)
    {
        $requestParam = json_decode(json_encode($request->all()));
        /**
         * @var \SwaggerUnAuth\Model\Filter $filter
         */
        $filter = ObjectSerializer::deserialize($requestParam, Filter::class, null);
        try {
            switch ($engine) {
                case 'elasticsearch': {
                    $elasticSearch = new Elasticsearch();
                    $items = $elasticSearch->getList($filter);
                    break;
                }
                default: {
                    return response(
                        new Error([
                            'application_error_code' => 404,
                            'debug' => '',
                            'message' => sprintf('engine %s not found', $engine)
                        ]), 404
                    );
                }
            }
        } catch (Exception $e) {
            return response(
                new Error([
                    'application_error_code' => 500,
                    'debug' => $e->getMessage(),
                    'message' => 'Internal Server Error'
                ]), 500
            );
        }
        return $items;
    }
}