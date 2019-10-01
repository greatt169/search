<?php

namespace App\Http\Controllers\Search\Web;

use App\Helpers\Serializer;
use App\Http\Controllers\Controller;
use App\UseCases\Catalog\Items;
use SwaggerSearch\ObjectSerializer;

class CatalogController extends Controller
{
    /**
     * @param Items $catalogItemsService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @todo refactor
     *
     */
    public function index(Items $catalogItemsService)
    {
        $apiInstance = new \SwaggerSearch\Api\CatalogApi();
        $apiInstance->getConfig()->setHost('http://' . $_SERVER['SERVER_ADDR'] . '/api');
        $engine = 'elasticsearch'; //  | Код поискового движка
        $index = 'auto'; //  | Код индекса
        $request = new \SwaggerSearch\Model\Request([
            "filter" => [
                "rangeParams" => [
                    [
                        "code" => "price",
                        "minValue" => 0,
                        "maxValue" => 1000000
                    ]
                ],
                "selectParams" => [
                    [
                        "code" => "color",
                        "values" => [
                            [
                                "value" => "white"
                            ]
                        ]
                    ]
                ]
            ],
            "aggregations" => [
                "items" => [
                    [
                        "field" => "color",
                        "type" => "checkbox"
                    ],
                    [
                        "field" => "price",
                        "type" => "range"
                    ]
                ]
            ]
        ]); // \SwaggerSearch\Model\Request |
        $page = 1; // int | Номер запрашиваемой страницы результата
        $page_size = 20; // int | Количество возвращаемых объектов на странице

        try {
            $response = $apiInstance->engineIndexIndexCatalogSearchPost($engine, $index, $request, $page, $page_size);
            $result = $catalogItemsService->getResult($response);
            //1dump($result);
        } catch (\Exception $e) {
            echo 'Exception when calling CatalogApi->engineIndexIndexCatalogSearchPost: ', $e->getMessage(), PHP_EOL;
        }
        return view('demo.catalog', ['result' => $result]);
    }
}