<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Search\Index\Indexer;

class IndexController extends Controller
{
    public function index()
    {
        $indexer = new Indexer();
        $data = include_once('data.php');
        foreach ($data as $dataItem) {
            $source = [
                'id' => $dataItem['id'],
                'attributes' => [
                    [
                        'in_query' => true,
                        'in_body' => true,
                        'code' => 'model',
                        'type' => 'string',
                        'multiple' => false,
                        'value' => $dataItem['model'],
                    ],
                    [
                        'in_query' => true,
                        'in_body' => true,
                        'code' => 'colors',
                        'type' => 'string',
                        'multiple' => true,
                        'value' => $dataItem['colors']
                    ],
                    [
                        'in_query' => true,
                        'in_body' => true,
                        'code' => 'year',
                        'type' => 'integer',
                        'multiple' => false,
                        'value' => $dataItem['year']
                    ],
                    [
                        'in_query' => true,
                        'in_body' => true,
                        'code' => 'price',
                        'type' => 'float',
                        'multiple' => false,
                        'value' => $dataItem['price']
                    ],
                    [
                        'in_query' => false,
                        'in_body' => true,
                        'code' => 'model_logo',
                        'type' => 'string',
                        'multiple' => false,
                        'value' => $dataItem['model_logo']
                    ]
                ]
            ];
            $indexObject = $indexer->buildIndexObject($source);
        }
        dd($indexObject);
    }
}
