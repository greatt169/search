<?php

namespace App\Search\Index\Manager;

use App\Search\Index\Entity\Document;
use App\Search\Index\Entity\DocumentAttribute;
use App\Search\Index\Interfaces\DocumentInterface;
use App\Search\Index\Interfaces\ManagerInterface;
use App\Search\Index\Interfaces\SourceInterface;

abstract class Base implements ManagerInterface
{
    /**
     * @var SourceInterface $source
     */
    protected $source;

    /**
     * @var DocumentInterface[] $documents
     */
    protected $documents;

    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @param array $source
     * @return DocumentInterface
     */
    protected function buildIndexObject(array $source)
    {
        $document = new Document();
        $document->setId($source['id']);
        $attributes = [];
        foreach ($source['attributes'] as $attribute) {
            $documentAttribute = new DocumentAttribute($attribute);
            $attributes[] = $documentAttribute;
        }
        $document->setAttributes($attributes);
        return $document;
    }

    public function buildIndexObjects()
    {
        $buildObjects = [];
        $sourceObjects = $this->source->getElementsForIndexing();
        foreach ($sourceObjects as $source) {
            $buildObjects[] = $this->buildIndexObject($source);
        }
        $this->documents = $buildObjects;
    }
}