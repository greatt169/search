<?php

namespace App\Search\Index\Manager;

use App\Exceptions\ApiException;
use App\Search\Entity\Interfaces\EntityInterface;
use App\Search\Index\Interfaces\SourceInterface;
use App\Search\Index\Listeners\SourceListener;
use Elasticsearch\Client;
use Exception;
use Illuminate\Support\Facades\Log;
use JsonStreamingParser\Parser;

class Elasticsearch extends Base
{
    protected $indexCreatedMessageTemplate = 'Index created. Index name [%s]';
    protected $aliasCreatedMessageTemplate = 'Alias created. Alias name [%s]';
    protected $aliasRemovedMessageTemplate = 'Alias to current index removed. Alias name [%s]';
    protected $indexRemovedMessageTemplate = 'Current index removed. Index name [%s]';
    /**
     * @var string
     */
    protected $index;

    /**
     * @var
     */
    protected $indexFrom;

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
    private $bulkSize = 500;

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
    protected $indexingStartMessageTemplate = 'Indexing started. Index from [%s], Index to [%s]';

    /**
     * @var string
     */
    protected $indexingFinishMessageTemplate = 'Indexing from %s to %s finished. Quantity total [%s]';

    /**
     * @var string
     */
    protected $indexingBatchParsedMessageTemplate = 'batch parsed. Quantity [%s] ';

    /**
     * @var string
     */
    protected $indexingBatchIndexedMessageTemplate = 'batch indexed. Quantity [%s] ';

    /**
     * @var string
     */
    protected $indexingFinishMemoryTemplate = 'Used memory calculated. Quantity: [%s]';

    /**
     * @var string
     */
    protected $indexMappingAppliedTemplate = 'Mapping applied. Answer [%s]. Rules applied [%s]';

    /**
     * @return null|string
     */
    protected function getCurrentIndex()
    {
        $index = $this->entity->getIndexByAlias($this->baseAliasName);
        return $index;
    }

    /**
     * @param bool $withSettings
     * @return array
     */
    protected function getIndexParams($withSettings = false)
    {
        $params = [
            'index' => $this->index
        ];
        if ($withSettings) {
            $settings = $this->getSource()->getIndexSettings();
            if(!$settings) {
                $settings = $this->getSettingsFromCurrentIndex();
            }
            $params['body']['settings'] = $settings;
        }
        return $params;
    }

    protected function init()
    {
        $this->baseAliasName = $this->entity->getIndexWithPrefix($this->source->getIndexName());
        $indexByAlias = $this->getCurrentIndex();
        if ($indexByAlias) {
            $this->index = $indexByAlias;
        } else {
            $this->index = $this->baseAliasName;
        }
    }

    /**
     * Elasticsearch constructor.
     * @param SourceInterface $source
     * @param EntityInterface $entity
     */
    public function __construct(SourceInterface $source, EntityInterface $entity)
    {
        parent::__construct($source, $entity);
    }

    public function createIndex()
    {
        $params = $this->getIndexParams(true);
        $this->getClient()->indices()->create($params);
        $this->log(sprintf($this->indexCreatedMessageTemplate, $params['index']));
        $this->addAlias($this->baseAliasName);
        $this->log(sprintf($this->aliasCreatedMessageTemplate, $this->baseAliasName));
    }

    function dropIndex()
    {
        $this->getClient()->indices()->delete($this->getIndexParams());
    }

    /**
     * @return int
     * @throws ApiException
     */
    public function indexAll()
    {
        $this->setMapping();
        $total = $this->indexAllElements();
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

    /**
     * @throws ApiException
     */
    public function reindex()
    {
        $this->init();
        $this->indexFrom = $this->getIndex();
        $newIndex = $this->getNewIndex();
        $this->log(sprintf($this->indexingStartMessageTemplate, $this->indexFrom, $newIndex));
        $this->createAuxIndexForReindex($newIndex);
        $total = $this->indexAll();
        $this->deleteCurrentIndex($this->indexFrom);
        $this->log(sprintf($this->indexingFinishMessageTemplate, $this->indexFrom, $newIndex, $total));
        $this->log(sprintf($this->indexingFinishMemoryTemplate, $this->memory->calculateUsedMemory()));
    }

    public function deleteIndex()
    {
        if ($this->indexExists($this->index)) {
            $this->removeAlias($this->baseAliasName, $this->index);
            $this->log(sprintf($this->aliasRemovedMessageTemplate, $this->baseAliasName));
            $this->dropIndex();
            $this->log(sprintf($this->indexRemovedMessageTemplate, $this->index));
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
        $indexByAlias = $this->getCurrentIndex();
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

    /**
     * @param string $message
     * @param string $level
     */
    public function log($message, $level = 'info')
    {
        $this->getLogger('devLogChannel')->$level($message);
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
     * @return int
     * @throws ApiException
     */
    protected function indexAllElements(): int
    {
        $listener = new SourceListener(function ($rawItems) {
            $this->log(sprintf($this->indexingBatchParsedMessageTemplate, count($rawItems)));
            $items = $this->source->getElementsForIndexing($rawItems);
            $params = ['body' => []];
            foreach ($items as $index => $document) {
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

                // Every 1000 documents stop and send the bulk request
                if ($i % $this->bulkSize == 0) {
                    $responses = $this->getClient()->bulk($params);
                    $this->log(sprintf($this->indexingBatchIndexedMessageTemplate, count($items)));
                    // erase the old bulk request
                    $params = ['body' => []];
                    // unset the bulk response when you are done to save memory
                    unset($responses);
                }
            }
            // Send the last batch if it exists
            if (!empty($params['body'])) {
                $responses = $this->getClient()->bulk($params);
                $this->log(sprintf($this->indexingBatchIndexedMessageTemplate, count($items)));
                // unset the bulk response when you are done to save memory
                unset($responses);
            }
        });
        $listener->setBatchSize($this->bulkSize);
        $stream = fopen($this->source->getDataLink(), 'r');
        try {
            $parser = new Parser($stream, $listener);
            $parser->parse();
            $total = $listener->getTotal();
            fclose($stream);
        } catch (Exception $e) {
            fclose($stream);
            throw new ApiException($e->getMessage(), $e->getTraceAsString(), 500);
        }
        return $total;
    }


    public function setMapping()
    {
        $mapping = $this->getSource()->getMappingForIndexing();
        if(!$mapping) {
            $mapping = $this->getMappingFromCurrentIndex();
        }
        $params = [
            'index' => $this->index,
            'body' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => $mapping
            ]
        ];
        $response = $this->getClient()->indices()->putMapping($params);
        $this->log(sprintf($this->indexMappingAppliedTemplate, json_encode($response), count($mapping)));
    }

    /**
     * @return array
     */
    protected function getSettingsFromCurrentIndex(): array
    {
        $settings = [];
        $index = $this->getCurrentIndex();
        $settingsParams = ['index' => $index];
        $response = $this->getClient()->indices()->getSettings($settingsParams);
        $responseSettings = $response[$index]['settings']['index'];
        $settings['analysis'] = $responseSettings['analysis'];
        return $settings;
    }

    /**
     * @return mixed
     */
    protected function getMappingFromCurrentIndex()
    {
        $index = $this->indexFrom;
        $mappingParams = ['index' => $index];
        $responseMapping = $this->getClient()->indices()->getMapping($mappingParams);
        $mapping = $responseMapping[$index]['mappings']['properties'];
        return $mapping;
    }
}