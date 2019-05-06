<?php

namespace App\Search\Index;

use App\Search\Entity\Index\DocumentAttribute;
use App\Search\Interfaces\Index\DocumentInterface;

class Indexer
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
}