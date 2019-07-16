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
        $source = new ElasticsearchSource('http://10.101.2.10/data.json');
        $data = $source->getElementsForIndexing();
        dump($data);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function reindex()
    {
        echo 111;
    }
}
