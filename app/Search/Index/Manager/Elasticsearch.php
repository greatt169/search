<?php

namespace App\Search\Index\Manager;

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

    /**
     * @var string
     */
    protected $aliasPrefix = 'alias_';

    /**
     * @var string
     */
    protected $reservePrefix = 'second_';

    private $bulkSize = 1000;

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
        $arSource = $this->getSource()->getElementsForIndexing();
        $params = ['body' => []];
        foreach ($arSource as $index => $document) {
            $i = $index + 1;
            $arDocAttributes = [];
            foreach ($document['attributes'] as $attribute) {
                $arDocAttributes[$attribute['code']] = $attribute['value'];
            }
            $params['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_type' => $this->type,
                    '_id' => $document['id']
                ]
            ];
            $params['body'][] = $arDocAttributes;
            // Every 1000 documents stop and send the bulk request
            if ($i % $this->bulkSize == 0) {
                $responses = $this->client->bulk($params);
                // erase the old bulk request
                $params = ['body' => []];
                // unset the bulk response when you are done to save memory
                unset($responses);
            }
        }
        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $responses = $this->client->bulk($params);

            // unset the bulk response when you are done to save memory
            unset($responses);
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

    /**
     * @param $aliasName
     * @param $index
     * @return bool
     */
    public function addAlias($aliasName, $index)
    {
        $params['body'] = [
            'actions' => [
                [
                    'add' => [
                        'index' => $index,
                        'alias' => $aliasName
                    ],
                ]
            ]
        ];

        try {
            $this->client->indices()->updateAliases($params);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param $aliasName
     * @param $index
     * @return bool
     */
    public function removeAlias($aliasName, $index)
    {
        $params['body'] = [
            'actions' => [
                [
                    'remove' => [
                        'index' => $index,
                        'alias' => $aliasName
                    ],
                ]
            ]
        ];

        try {
            $this->client->indices()->updateAliases($params);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function reindex()
    {

    }
}