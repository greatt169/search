<?php

namespace App\Search\Index\Manager;

use App\Search\Entity\Interfaces\EntityInterface;
use App\Search\Index\Interfaces\SourceInterface;
use App\Helpers\Interfaces\TimerInterface;
use Elasticsearch\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class Elasticsearch extends Base
{
    /**
     * @var string
     */
    protected $index;

    /**
     * @var string
     */
    protected $auxiliaryPrefix = 'auxiliary_';

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
    private $indexMappingAppliedTemplate = 'Mapping has applied: %s';

    protected function getIndexParams($withSettings = false)
    {
        $params = [
            'index' => $this->index
        ];
        if ($withSettings) {
            $params['body'] = $this->getSource()->getIndexSettings();
        }
        return $params;
    }

    /**
     * Elasticsearch constructor.
     * @param SourceInterface $source
     * @param EntityInterface $entity
     * @param TimerInterface|null $timer
     */
    public function __construct(SourceInterface $source, EntityInterface $entity, TimerInterface $timer = null)
    {
        parent::__construct($source, $entity, $timer);
        $this->baseAliasName = $this->entity->getIndexWithPrefix($this->source->getIndexName());
        $indexByAlias = $this->entity->getIndexByAlias($this->baseAliasName);
        if ($indexByAlias) {
            $this->index = $indexByAlias;
        } else {
            $this->index = $this->baseAliasName;
        }
    }

    public function createIndex()
    {
        $params = $this->getIndexParams(true);
        $this->getClient()->indices()->create($params);
        $this->addAlias($this->baseAliasName);
    }

    function dropIndex()
    {
        $this->getClient()->indices()->delete($this->getIndexParams());
    }

    public function indexAll()
    {
        $this->timer->start($this->indexAllTimerLabel);
        $this->setMapping();
        $total = $this->indexAllElements();
        $this->timer->end($this->indexAllTimerLabel);
        return $total;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->entity->getClient();
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param $aliasName
     * @param $index
     * @return bool
     */
    public function addAlias($aliasName, $index = null)
    {
        if ($index === null) {
            $index = $this->index;
        }
        $params['body'] = [
            'actions' => [
                [
                    'add' => [
                        'index' => $index,
                        'alias' => $this->entity->getAliasWithPrefix($aliasName)
                    ],
                ]
            ]
        ];

        $this->getClient()->indices()->updateAliases($params);
        return true;
    }

    /**
     * @param $aliasName
     * @param $index
     * @return bool
     */
    public function removeAlias($aliasName, $index = null)
    {
        if ($index === null) {
            $index = $this->index;
        }
        $alias = $this->entity->getAliasWithPrefix($aliasName);
        $params['body'] = [
            'actions' => [
                [
                    'remove' => [
                        'index' => $index,
                        'alias' => $alias
                    ],
                ]
            ]
        ];
        $aliases = $this->getClient()->indices()->getAliases();
        if (array_key_exists($alias, $aliases[$index])) {
            $this->getClient()->indices()->updateAliases($params);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $index
     * @return bool
     */
    public function indexExists($index)
    {
        try {
            $this->getClient()->indices()->get(['index' => $index]);
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
        if ($this->indexExists($this->index)) {
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
        $allIndices = $this->getClient()->indices()->get(['index' => '*']);
        return $allIndices;
    }

    /**
     * @return array|callable
     */
    public function getAllAliases()
    {
        $allAliases = $this->getClient()->indices()->getAliases();
        return $allAliases;
    }

    /**
     * @return mixed|string
     */
    public function getNewIndex()
    {
        $indexByAlias = $this->entity->getIndexByAlias($this->baseAliasName);
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

    /**
     * @return int
     */
    protected function indexAllElements(): int
    {
        $arSource = $this->getSource()->getElementsForIndexing();

        $params = ['body' => []];
        foreach ($arSource as $index => $document) {
            $this->timer->start($this->prepareBulkTimerLabel);
            $i = $index + 1;
            $arDocAttributes = [];

            foreach ($document['attributes'] as $attributeCode => $attributeValue) {
                $arDocAttributes[$attributeCode] = $attributeValue;
            }
            $arDocAttributes['raw_data'] = $document['raw_data'];
            $arDocAttributes['search_data'] = $document['search_data'];
            $arDocAttributes['ts'] = $this->startTime;
            $params['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_id' => $document['id']
                ]
            ];
            $params['body'][] = $arDocAttributes;
            $this->timer->end($this->prepareBulkTimerLabel);

            // Every 1000 documents stop and send the bulk request
            if ($i % $this->bulkSize == 0) {
                $this->timer->start($this->indexBulkTimerLabel);
                $responses = $this->getClient()->bulk($params);
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
            $responses = $this->getClient()->bulk($params);
            $this->timer->end($this->indexBulkTimerLabel);

            // unset the bulk response when you are done to save memory
            unset($responses);
        }

        $total = count($arSource);
        return $total;
    }


    public function setMapping()
    {
        $params = [
            'index' => $this->index,
            'body' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => $this->getSource()->getMappingForIndexing()
            ]
        ];
        $response = $this->getClient()->indices()->putMapping($params);
        $this->log(sprintf($this->indexMappingAppliedTemplate, json_encode($response)));
    }
}