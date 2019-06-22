<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;

use App\Search\Index\Manager\Elasticsearch;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;


class IndexController extends Controller
{
    public function index()
    {
        $indexer = new Elasticsearch(
            new ElasticsearchSource()
        );
        dump($indexer->getIndex());
        $client = $indexer->getClient();
        $params = [
            'index' => $indexer->getIndex(),
            'type' => $indexer->getType(),
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'colors' => 'black'
                                ]
                            ],
                            [
                                'match' => [
                                    'colors' => 'white'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $results = $client->search($params);
        dump($results);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function reindex()
    {

    }
}
