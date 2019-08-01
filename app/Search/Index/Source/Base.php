<?php

namespace App\Search\Index\Source;

use App\Exceptions\ApiException;
use App\Search\Index\Interfaces\SourceInterface;
use SwaggerSearch\Model\SourceIndex;
use SwaggerSearch\ObjectSerializer;

abstract class Base implements SourceInterface
{
    /**
     * @var null | SourceIndex
     */
    protected $sourceIndex = null;

    /**
     * @var null | string
     */
    protected $sourceIndexLink = null;

    /**
     * @var null | string
     */
    protected $dataLink = null;

    /**
     * @var string
     */
    protected $indexName;

    /**
     * Base constructor.
     * @param $indexName
     * @param $dataLink
     * @param null $sourceIndexLink
     * @throws ApiException
     */
    protected function __construct($indexName, $dataLink, $sourceIndexLink = null)
    {
        $this->dataLink = $dataLink;
        $this->sourceIndexLink = $sourceIndexLink;
        $this->indexName = $indexName;
        $this->checkFiles();
    }

    /**
     * @throws ApiException
     */
    public function checkFiles()
    {
        if(!file_exists($this->dataLink)) {
            throw new ApiException(sprintf('Could not open file %s', $this->dataLink), 'Internal Server Error');
        }
    }

    /**
     * @param $fileLink
     * @param $swaggerModelName
     * @return array|object|null
     */
    protected function getSwaggerModelByFile($fileLink, $swaggerModelName)
    {
        $swaggerObject = ObjectSerializer::deserialize(
            json_decode(file_get_contents($fileLink)),
            $swaggerModelName, null
        );
        return $swaggerObject;
    }

    /**
     * @return null | SourceIndex
     */
    public function getSourceIndex()
    {
        if(!$this->sourceIndexLink) {
            return new SourceIndex();
        }

        /**
         * @var SourceIndex $sourceIndex
         */
        if($this->sourceIndex == null) {
            $sourceIndex = $this->getSwaggerModelByFile($this->sourceIndexLink, SourceIndex::class);
            $this->sourceIndex = $sourceIndex;
        }
        return $this->sourceIndex;
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }

    /**
     * @return string|null
     */
    public function getDataLink(): ?string
    {
        return $this->dataLink;
    }
}