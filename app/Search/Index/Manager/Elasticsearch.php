<?php

namespace App\Search\Index\Manager;

use App\Search\Index\Interfaces\SourceInterface;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Exception;

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
    protected $auxiliaryPrefix = 'auxiliary_';

    /**
     * @var string
     */
    protected $aliasPrefix = 'alias_';

    /**
     * @var int
     */
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
        $this->addAlias($this->index);
    }

    function dropIndex()
    {
        $this->client->indices()->delete($this->getIndexParams());
        $this->removeAlias($this->index);
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
     * @return bool
     */
    public function addAlias($aliasName)
    {
        $params['body'] = [
            'actions' => [
                [
                    'add' => [
                        'index' => $this->index,
                        'alias' => $this->getAliasWithPrefix($aliasName)
                    ],
                ]
            ]
        ];

        $this->client->indices()->updateAliases($params);
        return true;
    }

    /**
     * @param $aliasName
     * @return bool
     */
    public function removeAlias($aliasName)
    {
        $params['body'] = [
            'actions' => [
                [
                    'remove' => [
                        'index' => $this->index,
                        'alias' => $this->getAliasWithPrefix($aliasName)
                    ],
                ]
            ]
        ];

        $this->client->indices()->updateAliases($params);
        return true;
    }

    /**
     * @param $aliasName
     * @return string
     */
    public function getAliasWithPrefix($aliasName) {
        return $this->aliasPrefix . $aliasName;
    }

    /**
     * @param $index
     * @return bool
     */
    public function indexExists($index)
    {
        try {
            $this->client->indices()->get(['index' => $index]);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param $index
     * @return mixed|string
     */
    protected function getIndexName($index)
    {
        if (strpos($index, $this->auxiliaryPrefix) !== false) {
            return str_replace($this->auxiliaryPrefix, '', $index);
        } else {
            return $this->auxiliaryPrefix . $index;
        }
    }

    /**
     * @param string $aliasName код alias
     *
     * @return bool | string
     * @throws Exception
     */
    public function getIndexByAlias($aliasName)
    {
        $aliases = $this->client->indices()->getAliases();
        $aliasWithPrefix = $this->getAliasWithPrefix($aliasName);
        foreach ($aliases as $index => $aliasMapping) {
            if (array_key_exists($aliasWithPrefix, $aliasMapping['aliases'])) {
                return $index;
            }
        }
        throw new Exception(sprintf('No Indexes by alias "%s"', $aliasName), 404);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function reindex()
    {
        $index = $this->getIndex();
        $indexByAlias = $this->getIndexByAlias($index);
        dd($this->getIndexName($indexByAlias));
    }
}