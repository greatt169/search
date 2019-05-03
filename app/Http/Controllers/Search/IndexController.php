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
                        'code' => 'model',
                        'type' => 'string',
                        'multiple' => false,
                        'value' => $dataItem['model']
                    ],
                    [
                        'code' => 'colors',
                        'type' => 'string',
                        'multiple' => true,
                        'value' => $dataItem['colors']
                    ],
                    [
                        'code' => 'year',
                        'type' => 'integer',
                        'multiple' => false,
                        'value' => $dataItem['year']
                    ],
                    [
                        'code' => 'price',
                        'type' => 'float',
                        'multiple' => false,
                        'value' => $dataItem['price']
                    ]
                ]
            ];
            $indexObject = $indexer->buildIndexObject($source);
        }
        dd($indexObject);
    }
}
