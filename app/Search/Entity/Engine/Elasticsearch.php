<?php

namespace App\Search\Entity\Engine;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Log;

class Elasticsearch extends Base
{
    /**
     * @var Client
     */
    protected $client = null;

    protected function getHosts()
    {
        $hosts = explode(',', config('search.index.elasticsearch.hosts'));
        return $hosts;
    }

    protected function isLogsEnable()
    {
        return config('search.index.elasticsearch.log_save');
    }

    /**
     * Elasticsearch constructor.
     * @param string $index|null
     */
    public function __construct(string $index = null)
    {
        parent::__construct($index);

    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if($this->client !== null) {
            return $this->client;
        }
        $clientBuild = ClientBuilder::create()->setHosts($this->getHosts());
        if($this->isLogsEnable()) {
            $clientBuild->setLogger(Log::channel('fullLogChannel'));
        }
        $this->client = $clientBuild->build();
        return $this->client;
    }
}