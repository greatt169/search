<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\ReindexRequest;
use App\Search\Index\Manager\Elasticsearch;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;


class IndexController extends Controller
{
    public function index()
    {
        $source = new ElasticsearchSource('http://10.101.2.10/data.json');
        $data = $source->getElementsForIndexing();
        dump($data);
    }

    /**
     * @param ReindexRequest $request
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function reindex(ReindexRequest $request)
    {
        $sourceLink = $request->getValid('link');
        $indexer = new Elasticsearch(
            new ElasticsearchSource($sourceLink),
            new ElasticsearchEntity()
        );
        $indexer->reindex();
        $displayResultMessages = $indexer->getDisplayResultMessages();
        return $displayResultMessages;
    }
}
