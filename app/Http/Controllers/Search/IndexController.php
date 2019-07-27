<?php

namespace App\Http\Controllers\Search;

use App\Events\Search\NewFeedEvent;
use App\Http\Controllers\Controller;

use App\Http\Requests\Api\ReindexRequest;
use App\Search\Index\Listeners\SourceListener;
use App\Search\Index\Manager\Elasticsearch;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;
use Exception;
use App\Exceptions\ApiException;
use SwaggerSearch\Model\ListItem;
use SwaggerSearch\Model\ListItemAttributeValue;
use SwaggerSearch\Model\ListItemMultipleAttribute;
use SwaggerSearch\Model\ListItemSingleAttribute;
use SwaggerSearch\ObjectSerializer;


class IndexController extends Controller
{

    /**
     * @param ListItemAttributeValue $attributeValue
     * @return string
     */
    protected function getAttributeVal($attributeValue) {
        $val = $attributeValue->getCode();
        if($val === null) {
            $val = $attributeValue->getValue();
        }
        return $val;
    }

    /**
     * @throws \Exception
     */
    public function index()
    {
        event(new NewFeedEvent('/var/www/public/data.json', null));
    }

    /**
     * @param ReindexRequest $request
     * @return array
     * @throws ApiException
     */
    public function reindex(ReindexRequest $request)
    {
        $sourceLink = $request->getValid('data');
        $indexer = new Elasticsearch(
            new ElasticsearchSource($sourceLink),
            new ElasticsearchEntity()
        );
        $indexer->reindex();
        $displayResultMessages = $indexer->getDisplayResultMessages();
        return $displayResultMessages;
    }
}
