<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Search\Query\Request\Elasticsearch;
use Exception;
use Illuminate\Http\Request;
use SwaggerUnAuth\Model\Error;
use SwaggerUnAuth\Model\CatalogListFilter;
use SwaggerUnAuth\ObjectSerializer;

class SearchController extends Controller
{
    public function catalogList(Request $request)
    {
        $requestParam = json_decode(json_encode($request->all()));
        $engine = $request->get('engine');
        $index = $request->get('index');
        /**
         * @var CatalogListFilter $filter
         */
        $filter = ObjectSerializer::deserialize($requestParam, CatalogListFilter::class, null);
        try {
            switch ($engine) {
                case 'elasticsearch': {
                    $elasticSearch = new Elasticsearch();
                    $items = $elasticSearch->postCatalogList($filter);
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