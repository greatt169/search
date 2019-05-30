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
        $indexer->indexAll();
        $client = $indexer->getClient();

        $params = [
            'index' => $indexer->getIndex(),
            'type' => $indexer->getType(),
            'body' => [
                'query' => [
                    'match' => [
                        'model' => 'Polo'
                    ]
                ]
            ]
        ];

        $results = $client->search($params);
        dd($results);
    }

    public function reindex()
    {
        $indexer = new Elasticsearch(
            new ElasticsearchSource()
        );
        $indexer->dropIndex();
        $indexer->createIndex();
        return 'done';
    }
}
