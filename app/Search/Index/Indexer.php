<?php

namespace App\Search\Index;

use App\Search\Entity\Index\Document;
use App\Search\Entity\Index\DocumentAttribute;
use App\Search\Interfaces\Index\DocumentInterface;

class Indexer
{
    /**
     * @param array $source
     * @return DocumentInterface
     */
    public function buildIndexObject(array $source)
    {
        $document = new Document();
        $document->id = $source['id'];
        $attributes = [];
        foreach ($source['attributes'] as $attribute) {
            $documentAttribute = new DocumentAttribute($attribute);
            $attributes[] = $documentAttribute;
        }
        $document->attributes = $attributes;
        return $document;
    }
}