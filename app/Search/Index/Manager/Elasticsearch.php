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
     * @var string
     */
    protected $baseAliasName;

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

    /**
     * Elasticsearch constructor.
     * @param SourceInterface $source
     * @throws Exception
     */
    public function __construct(SourceInterface $source)
    {
        parent::__construct($source);
        $this->client = ClientBuilder::create()->setHosts($this->getHosts())->build();
        $this->baseAliasName = $this->source->getIndexName();
        try {
            $this->index = $this->getIndexByAlias($this->baseAliasName);
        } catch (Exception $e) {
            $this->index = $this->baseAliasName;
        }
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
        $this->addAlias($this->baseAliasName);
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
    public function addAlias($aliasName, $index = null)
    {
        if($index === null) {
            $index = $this->index;
        }
        $params['body'] = [
            'actions' => [
                [
                    'add' => [
                        'index' => $index,
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
     * @param $index
     * @return bool
     */
    public function removeAlias($aliasName, $index = null)
    {
        if($index === null) {
            $index = $this->index;
        }
        $params['body'] = [
            'actions' => [
                [
                    'remove' => [
                        'index' => $index,
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
    protected function getNextIndexName($index)
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
        $currentIndex = $this->getIndex();
        $indexByAlias = $this->getIndexByAlias($this->baseAliasName);
        $newIndex = $this->getNextIndexName($indexByAlias);

        $this->setIndex($newIndex);
        if($this->indexExists($newIndex)) {
            $this->dropIndex();
        }
        $this->createIndex();
        $this->indexAll();

        $this->addAlias($this->baseAliasName, $newIndex);
        $this->removeAlias($this->baseAliasName, $currentIndex);

        $this->setIndex($currentIndex);
        $this->dropIndex();
    }

    /**
     * @param string $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return array|callable
     */
    public function getAllIndices()
    {
        $allIndices = $this->client->indices()->get(['index' => '*']);
        return $allIndices;
    }

    /**
     * @return array|callable
     */
    public function getAllAliases()
    {
        $allAliases = $this->client->indices()->getAliases();
        return $allAliases;
    }
}