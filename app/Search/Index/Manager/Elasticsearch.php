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
        // TODO: Implement indexAll() method.
    }

    public function removeAll()
    {
        // TODO: Implement removeAll() method.
    }

    public function indexElements($filter = null)
    {
        // TODO: Implement indexElements() method.
    }

    public function indexElement($id)
    {
        // TODO: Implement indexElement() method.
    }

    public function prepareElementsForIndexing()
    {
        dd($this->documents);
        foreach ($this->documents as $document) {

        }
    }
}