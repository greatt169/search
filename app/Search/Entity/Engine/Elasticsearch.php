<?php

namespace App\Search\Entity\Engine;

use App\Exceptions\ApiException;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Log;
use SwaggerSearch\Model\ListItem;
use \Throwable;

class Elasticsearch extends Base
{
    /**
     * @var Client
     */
    protected $client = null;

    protected $aliasPrefix = 'alias_';

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
     * @param string $aliasName код alias
     *
     * @return null | string
     * @throws ApiException
     */
    public function getIndexByAlias($aliasName)
    {
        try {
            $aliases = $this->getClient()->indices()->getAliases();
            $aliasWithPrefix = $this->getAliasWithPrefix($aliasName);
            foreach ($aliases as $index => $aliasMapping) {
                if (array_key_exists($aliasWithPrefix, $aliasMapping['aliases'])) {
                    return $index;
                }
            }
        } catch (Throwable $e) {
            throw new ApiException(
                'Connect error. Try later',
                $e->getMessage(),
                500
            );
        }
        return null;
    }

    /**
     * @param $aliasName
     * @return string
     */
    public function getAliasWithPrefix($aliasName)
    {
        return $this->aliasPrefix . $aliasName;
    }

    /**
     * @param $index
     * @return string
     */
    public function getIndexWithPrefix($index)
    {
        return config('search.index.elasticsearch.prefix') . $index;
    }

    /**
     * Elasticsearch constructor.
     * @param string $index |null
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

        if ($this->client !== null) {
            return $this->client;
        }
        $clientBuild = ClientBuilder::create()->setHosts($this->getHosts());
        if ($this->isLogsEnable()) {
            $clientBuild->setLogger(Log::channel('elasticsearch_full'));
        }
        $this->client = $clientBuild->build();
        return $this->client;
    }

    /**
     * @param $hit
     * @return ListItem $listItem
     */
    public function getConvertedEngineData($hit): ListItem
    {
        $source = $hit['_source'];
        $listItem = new ListItem();
        if (array_key_exists('raw_data', $source)) {
            $listItem = unserialize($source['raw_data']);
        }
        return $listItem;
    }
}