<?php

namespace App\Search\Index\Manager;

use App\Search\Index\Interfaces\DocumentAttributeInterface;
use App\Search\Index\Interfaces\SourceInterface;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class Elasticsearch extends Base
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $index;

    /**
     * @var string
     */
    protected $type;

    protected function getIndexParams()
    {
        $params = [
            'index' => $this->index,
        ];
        return $params;
    }

    public function __construct(SourceInterface $source)
    {
        parent::__construct($source);
        $this->client = ClientBuilder::create()->setHosts($this->getHosts())->build();
        $this->index = $this->source->getIndexName();
        $this->type = $this->source->getTypeName();
    }

    protected function getHosts()
    {
        $hosts = explode(',', config('search.index.elasticsearch.hosts'));
        return $hosts;
    }

    public function createIndex()
    {
        $this->client->indices()->create($this->getIndexParams());
    }

    function dropIndex()
    {
        $this->client->indices()->delete($this->getIndexParams());
    }

    public function indexAll()
    {
        $prepared = $this->prepareElementsForIndexing();
        foreach ($prepared as $item) {
           $this->client->index($item);
        }
    }

    public function removeAll()
    {
        $this->dropIndex();
        $this->createIndex();
    }

    public function indexElement($id)
    {
        // TODO: Implement indexElement() method.
    }

    public function prepareElementsForIndexing()
    {
        $arSource = $this->getSource()->getElementsForIndexing();
        $arPreparedElements = [];
        foreach ($arSource as $document) {
            $arDocAttributes = [];
            foreach ($document['attributes'] as $attribute) {
                $arDocAttributes[$attribute['code']] = $attribute['value'];
            }
            $arDoc = [
                'index' => $this->index,
                'type' => $this->type,
                'id' => $document['id'],
                'body' => $arDocAttributes
            ];
            $arPreparedElements[] = $arDoc;
        }
        return $arPreparedElements;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}