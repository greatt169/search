<?php

namespace App\Search\Index\Source;

use SwaggerSearch\Model\DisplayListItem;
use SwaggerSearch\Model\DisplayListItemAttributeValue;
use SwaggerSearch\Model\DisplayListItemMultipleAttribute;
use SwaggerSearch\Model\DisplayListItemSingleAttribute;
use SwaggerSearch\Model\SourceIndex;
use SwaggerSearch\Model\SourceIndexMapping;

class Elasticsearch extends Base
{
    protected $indexName = 'auto';

    public function __construct()
    {
        parent::__construct();
    }

    public function getIndexSettings()
    {
        $settings = [
            'settings' => [
                'analysis' => [
                    'char_filter' => [
                        'replace' => [
                            'type' => 'mapping',
                            'mappings' => [
                                '&=> and '
                            ],
                        ],
                    ],
                    'filter' => [
                        'word_delimiter' => [
                            'type' => 'word_delimiter',
                            'split_on_numerics' => false,
                            'split_on_case_change' => true,
                            'generate_word_parts' => true,
                            'generate_number_parts' => true,
                            'catenate_all' => true,
                            'preserve_original' => true,
                            'catenate_numbers' => true,
                        ],
                        'trigrams' => [
                            'type' => 'ngram',
                            'min_gram' => 3,
                            'max_gram' => 4,
                        ],
                        "russian_stop" => [
                            "type" => "stop",
                            "stopwords" => "_russian_"
                        ],
                        "russian_keywords" => [
                            "type" => "keyword_marker",
                            "keywords" => []
                        ],
                        "russian_stemmer" => [
                            "type" => "stemmer",
                            "language" => "russian"
                        ]
                    ],
                    'analyzer' => [
                        'default' => [
                            'type' => 'custom',
                            'char_filter' => [
                                'html_strip',
                                'replace',
                            ],
                            'tokenizer' => 'whitespace',
                            'filter' => [
                                'lowercase',
                                'word_delimiter',
                                'trigrams',
                                'russian_stop',
                                'russian_keywords',
                                'russian_stemmer',
                            ],
                        ],
                    ],
                ],
            ]
        ];

        return $settings;
    }

    /**
     * @param DisplayListItemAttributeValue $attributeValue
     * @return string
     */
    protected function getAttributeVal($attributeValue) {
        $val = $attributeValue->getCode();
        if($val === null) {
            $val = $attributeValue->getValue();
        }
        return $val;
    }

    public function getElementsForIndexing()
    {
        /**
         * @var SourceIndex $sourceIndex
         */
        $sourceIndex = $this->getSourceIndex();
        $elementsForIndexing = [];
        $data = $sourceIndex->getItems();

        /**
         * @var DisplayListItem $dataItem
         */
        foreach ($data as $dataItem) {
            $source = [];
            $searchData = [];
            $sourceAttributes = [];
            $source['id'] = $dataItem->getId();

            $singleAttributes = $dataItem->getSingleAttributes();
            $multipleAttributes = $dataItem->getMultipleAttributes();

            /**
             * @var DisplayListItemSingleAttribute $attribute
             */
            foreach ($singleAttributes as $attribute) {
                /**
                 * @var DisplayListItemAttributeValue $attributeValue
                 */
                $attributeCode = $attribute->getCode();
                $attributeValue = $attribute->getValue();
                if($attributeValue) {
                    $sourceAttributes[$attributeCode] = $this->getAttributeVal($attributeValue);
                    $searchData[$attribute->getCode()] = $attribute->getName() . ' ' . $attributeValue->getValue();
                }
            }

            /**
             * @var DisplayListItemMultipleAttribute $attribute
             */
            foreach ($multipleAttributes as $attribute) {
                $attributeCode = $attribute->getCode();
                $multipleAttributeValues = $attribute->getValues();
                $sourceAttributeValues = [];
                /**
                 * @var DisplayListItemAttributeValue $attributeValue
                 */
                foreach ($multipleAttributeValues as $attributeValue) {
                    if($attributeValue) {
                        $sourceAttributeValues[] = $this->getAttributeVal($attributeValue);
                        $searchData[$attribute->getCode()] = $attribute->getName() . ' ' . $attributeValue->getValue();
                    }
                }
                $sourceAttributes[$attributeCode] = $sourceAttributeValues;
            }
            $source['attributes'] = $sourceAttributes;
            $rawData = serialize($dataItem);
            $source['raw_data'] = $rawData;
            $source['search_data'] = $searchData;
            $elementsForIndexing[] = $source;
        }
        return $elementsForIndexing;
    }

    public function getMappingForIndexing()
    {
        /**
         * @var SourceIndex $sourceIndex
         */
        $sourceIndex = $this->getSourceIndex();
        $mappingParams = [];
        $mapping = $sourceIndex->getMapping();
        /**
         * @var SourceIndexMapping $attributeMapping
         */
        foreach ($mapping as $attributeCode => $attributeMapping) {
            $mappingParams[$attributeCode] = [
                'type' => $attributeMapping->getType()
            ];
            $mappingParams['search_data']['properties'][$attributeCode] = [
                'type' => 'text',
                "analyzer" => 'default'
            ];
            $mappingParams['ts'] = [
                'type' => 'integer'
            ];
        }
        return $mappingParams;
    }
}