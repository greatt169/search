<?php

namespace App\Demo\Controllers;

use App\Demo\Helpers\References;
use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @todo refactor
     *
     */
    public function index(References $references)
    {
        $apiUrl = 'http://' . $_SERVER['SERVER_ADDR'] . '/api/elasticsearch/_index/auto/catalog/search?page=1&pageSize=20';

        $requestParams = [
            "aggregations" => [
                "items" => [
                    [
                        "field" => "color",
                        "type" => "checkbox"
                    ],
                    [
                        "field" => "year",
                        "type" => "checkbox"
                    ],
                    [
                        "field" => "price",
                        "type" => "range"
                    ]
                ]
            ],
            "filter" => [
                "selectParams" => [],
                "rangeParams" => []
            ]
        ];

        $filterParams = json_encode($requestParams);
        $referencesData = json_encode($references->getTree());

        return view(
            'demo.catalog',
            [
                'apiUrl' => $apiUrl,
                'references' => $referencesData,
                'filterParams' => $filterParams
            ]
        );
    }
}