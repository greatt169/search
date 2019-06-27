<?php

namespace App\Search\Index\Manager;

use App\Search\Entity\Interfaces\EntityInterface;
use App\Search\Index\Interfaces\SourceInterface;
use App\Search\Index\Interfaces\TimerInterface;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Exception;
use Illuminate\Support\Facades\Log;

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

    /**
     * @var string
     */
    protected $fullLogChannel = 'elasticsearch_full';

    /**
     * @var string
     */
    protected $devLogChannel = 'elasticsearch_dev';

    /**
     * @var string
     */
    protected $indexingStartMessageTemplate = 'Indexing from %s to %s has started';

    /**
     * @var string
     */
    protected $indexingFinishMessageTemplate = 'Indexing from %s to %s has finished. Quantity of documents: %s';

    /**
     * @var array
     */
    protected $displayResultMessages = [];

    /**
     * @var string
     */
    protected $indexAllTimerLabel = 'full_index';

    /**
     * @var string
     */
    protected $prepareBulkTimerLabel = 'prepare_bulk';

    /**
     * @var string
     */
    protected $indexBulkTimerLabel = 'index_bulk';

    protected function getIndexParams()
    {
        $params = [
            'index' => $this->index,
        ];
        return $params;
    }

    private function isLogsEnable()
    {
        return config('search.index.elasticsearch.log_save');
    }

    /**
     * Elasticsearch constructor.
     * @param SourceInterface $source
     * @param EntityInterface $entity
     * @param TimerInterface|null $timer
     */
    public function __construct(SourceInterface $source, EntityInterface $entity, TimerInterface $timer = null)
    {
        parent::__construct($source, $entity);
        $clientBuild = ClientBuilder::create()->setHosts($this->getHosts());
        if($this->isLogsEnable()) {
            $clientBuild->setLogger($this->getLogger('fullLogChannel'));
        }
        $this->client = $clientBuild->build();
        $this->baseAliasName = $this->getSourceIndex();
        try {
            $this->index = $this->getIndexByAlias($this->baseAliasName);
        } catch (Exception $e) {
            $this->index = $this->baseAliasName;
        }
        $this->type = $this->source->getTypeName();
    }

    private function getSourceIndex()
    {
        $sourceIndex = config('search.index.elasticsearch.prefix') . $this->source->getIndexName();
        return $sourceIndex;
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
        $this->timer->start($this->indexAllTimerLabel);
        $arSource = $this->getSource()->getElementsForIndexing();
        $params = ['body' => []];
        foreach ($arSource as $index => $document) {
            $this->timer->start($this->prepareBulkTimerLabel);
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
            $this->timer->end($this->prepareBulkTimerLabel);

            // Every 1000 documents stop and send the bulk request
            if ($i % $this->bulkSize == 0) {
                $this->timer->start($this->indexBulkTimerLabel);
                $responses = $this->client->bulk($params);
                $this->timer->end($this->indexBulkTimerLabel);
                // erase the old bulk request
                $params = ['body' => []];
                // unset the bulk response when you are done to save memory
                unset($responses);
            }
        }
        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $this->timer->start($this->indexBulkTimerLabel);
            $responses = $this->client->bulk($params);
            $this->timer->end($this->indexBulkTimerLabel);

            // unset the bulk response when you are done to save memory
            unset($responses);
        }

        $total = count($arSource);
        $this->timer->end($this->indexAllTimerLabel);
        return $total;
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
     * @return null | string
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
        return null;
    }

    /**
     * @return string
     */
    public function reindex()
    {
        $currentIndex = $this->getIndex();
        $newIndex = $this->getNewIndex();
        $this->log(sprintf($this->indexingStartMessageTemplate, $currentIndex, $newIndex));
        $this->createAuxIndexForReindex($newIndex);
        $total = $this->indexAll();
        $this->deleteCurrentIndex($currentIndex);
        $this->log(sprintf($this->indexingFinishMessageTemplate, $currentIndex, $newIndex, $total));
        $arIntervalsSum = $this->timer->getIntervalsSum();
        foreach ($arIntervalsSum as $field => $value) {
            $this->log($field . ': ' . $value);
        }
    }

    public function deleteIndex()
    {
        if($this->indexExists($this->index)) {
            $this->removeAlias($this->baseAliasName, $this->index);
            $this->dropIndex();
        }
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

    /**
     * @return mixed|string
     */
    public function getNewIndex()
    {
        $indexByAlias = $this->getIndexByAlias($this->baseAliasName);
        if ($indexByAlias === null) {
            $indexByAlias = $this->baseAliasName;
        }
        $newIndex = $this->getNextIndexName($indexByAlias);
        return $newIndex;
    }

    /**
     * @param $newIndex
     */
    public function createAuxIndexForReindex($newIndex)
    {
        $this->setIndex($newIndex);
        $this->deleteIndex();
        $this->createIndex();
        $this->addAlias($this->baseAliasName, $newIndex);
    }

    /**
     * @param $currentIndex
     */
    public function deleteCurrentIndex($currentIndex)
    {
        $this->setIndex($currentIndex);
        $this->deleteIndex();
    }

    public function removeAll()
    {
        // TODO: Implement removeAll() method.
    }

    public function indexElement($id)
    {
        // TODO: Implement indexElement() method.
    }

    /**
     * @param string $message
     * @param string $level
     */
    protected function log($message, $level = 'info')
    {
        $this->getLogger('devLogChannel')->$level($message);
        $this->displayResultMessages[] = $message;
    }

    /**
     * @param $code
     * @return mixed
     */
    protected function getLogger($code)
    {
        return Log::channel($this->$code);
    }

    /**
     * @return array
     */
    public function getDisplayResultMessages()
    {
        return $this->displayResultMessages;
    }
}