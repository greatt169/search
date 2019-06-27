<?php

namespace App\Search\Entity\Engine;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Psr\Log\LoggerInterface;

class Elasticsearch extends Base
{
    /**
     * @var Client
     */
    protected $client;

    protected function getHosts()
    {
        $hosts = explode(',', config('search.index.elasticsearch.hosts'));
        return $hosts;
    }

    /**
     * Elasticsearch constructor.
     * @param string $index|null
     * @param LoggerInterface|null $logger
     */
    public function __construct(string $index = null, LoggerInterface $logger = null)
    {
        parent::__construct($index);
        $clientBuild = ClientBuilder::create()->setHosts($this->getHosts());
        if($logger !== null) {
            $clientBuild->setLogger($logger);
        }
        $this->client = $clientBuild->build();

    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}