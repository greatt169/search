<?php

namespace App\Search\Index\Manager;

use App\Search\Index\Interfaces\DocumentInterface;
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
            'index' => $this->index
        ];
        return $params;
    }

    public function __construct(DocumentInterface $document, SourceInterface $source)
    {
        parent::__construct($document, $source);
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
        $arPreparedElements = [];
        foreach ($this->documents as $document) {
            $arPreparedElements[] = [
                'index' => $this->index,
                'type' => $this->type,
                'id' => $document->id,
                'body' => [
                    'testField' => 'abc'
                ]
            ];
        }
        return $arPreparedElements;
    }
}