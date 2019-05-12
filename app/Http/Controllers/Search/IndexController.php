<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;

use App\Search\Index\Entity\Document;
use App\Search\Index\Manager\Elasticsearch;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;

class IndexController extends Controller
{
    public function index()
    {
        $indexer = new Elasticsearch(
            new Document(),
            new ElasticsearchSource()
        );
        $indexer->dropIndex();
        $indexer->createIndex();
        $indexer->buildIndexObjects();
        $indexer->prepareElementsForIndexing();
        $indexer->indexAll();
        //$indexer->removeAll();

        $client = $indexer->getClient();

        $params = [
            'index' => $indexer->getIndex(),
            'type' => $indexer->getType(),
            'id' => 3
        ];

        $results = $client->get($params);
        dd($results);
    }
}
