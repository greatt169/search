<?php

namespace App\Search\Index\Manager;

use App\Search\Index\Entity\DocumentAttribute;
use App\Search\Index\Interfaces\DocumentInterface;
use App\Search\Index\Interfaces\ManagerInterface;

abstract class Base implements ManagerInterface
{
    /**
     * @var DocumentInterface $document
     */
    private $document;

    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
    }

    /**
     * @param array $source
     * @return DocumentInterface
     */
    public function buildIndexObject(array $source)
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

    abstract public function createIndex($index);

    abstract function dropIndex($index);

    abstract public function indexAll($index);

    abstract public function removeAll($index);

    abstract public function indexElements($filter = null);

    abstract public function indexElement($id);

    abstract public function prepareElementsForIndexing($filter = null);
}