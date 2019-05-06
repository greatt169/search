<?php

namespace App\Search\Index\Manager;

use App\Search\Index\Entity\DocumentAttribute;
use App\Search\Index\Interfaces\DocumentInterface;
use App\Search\Index\Interfaces\ManagerInterface;
use App\Search\Index\Interfaces\SourceInterface;

abstract class Base implements ManagerInterface
{
    /**
     * @var SourceInterface $source
     */
    private $source;

    /**
     * @var DocumentInterface $document
     */
    private $document;

    public function __construct(DocumentInterface $document, SourceInterface $source)
    {
        $this->document = $document;
        $this->source = $source;
    }

    /**
     * @param array $source
     * @return DocumentInterface
     */
    protected function buildIndexObject(array $source)
    {
        $this->document->id = $source['id'];
        $attributes = [];
        foreach ($source['attributes'] as $attribute) {
            $documentAttribute = new DocumentAttribute($attribute);
            $attributes[] = $documentAttribute;
        }
        $this->document->attributes = $attributes;
        return $this->document;
    }

    public function buildIndexObjects()
    {
        $builObjects = [];
        $sourceObjects = $this->source->getElementsForIndexing();
        foreach ($sourceObjects as $source) {
            $builObjects[] = $this->buildIndexObject($source);
        }

        return $builObjects;
    }
}