<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;

use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;
use App\Search\Index\Manager\Elasticsearch;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
use SwaggerSearch\Model\ListItem;
use SwaggerSearch\Model\ListItems;
use SwaggerSearch\ObjectSerializer;


class IndexController extends Controller
{
    public function index()
    {

        $indexer = new Elasticsearch(
            new ElasticsearchSource(),
            new ElasticsearchEntity()
        );
        //$indexer->reindex();

        $indexer->getSource()->getElementsForIndexing();

        $client = $indexer->getClient();
        $params = [
            'index' => $indexer->getIndex(),
            'body' => [

                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'insurance' => 'osago'
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
