<?php

return [
    "mapping" => [
        "type" => [
            "type" => "keyword",
        ], "name" => [
            "type" => "text",
        ], "model" => [
            "type" => "keyword",
        ], "color" => [
            "type" => "keyword",
        ], "insurance" => [
            "type" => "keyword",
        ], "brand" => [
            "type" => "keyword",
        ], "price" => [
            "type" => "float",
        ], "year" => [
            "type" => "integer",
        ]
    ],
    "indexSettings" => [
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
    ],
    "items" => [
        [
            "id" => 1,
            "singleAttributes" => [
                [
                    "code" => 'name',
                    "name" => "Название",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "Внедорожник BMW-X5"
                    ]
                ],
                [
                    "code" => 'type',
                    "name" => "Тип элемента",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "element"
                    ]
                ],
                [
                    "code" => 'brand',
                    "name" => "Марка машины",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => 'bmw',
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "BMV"
                    ]
                ],
                [
                    "code" => 'model',
                    "name" => "Модель машины",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => 'x5',
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "X5"
                    ]
                ],
                [
                    "code" => 'year',
                    "name" => "Год выпуска",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "2018"
                    ]
                ],
                [
                    "code" => 'price',
                    "name" => "Цена",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => '5000000'
                    ]
                ]
            ],
            "multipleAttributes" => [
                [
                    "code" => 'color',
                    "name" => "Цвет",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "values" => [
                        [
                            "code" => 'red',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "Красный"
                        ],
                        [
                            "code" => 'blue',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "Голубой"
                        ]
                    ]
                ],
                [
                    "code" => 'insurance',
                    "name" => "Страховка",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "values" => [
                        [
                            "code" => 'kasko',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "КАСКО"
                        ],
                        [
                            "code" => 'osago',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "ОСАГО"
                        ]
                    ]
                ]
            ]
        ],
        [
            "id" => 2,
            "singleAttributes" => [
                [
                    "code" => 'name',
                    "name" => "Название",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "Старая Лада Гранта"
                    ]
                ],
                [
                    "code" => 'type',
                    "name" => "Тип элемента",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "element"
                    ]
                ],
                [
                    "code" => 'brand',
                    "name" => "Марка машины",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => 'vaz',
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "Лада"
                    ]
                ],
                [
                    "code" => 'model',
                    "name" => "Модель машины",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => 'granta_new',
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "Granta Новая"
                    ]
                ],
                [
                    "code" => 'year',
                    "name" => "Год выпуска",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "1990"
                    ]
                ],
                [
                    "code" => 'price',
                    "name" => "Цена",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => '50000'
                    ]
                ]
            ],
            "multipleAttributes" => [
                [
                    "code" => 'color',
                    "name" => "Цвет",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "values" => [
                        [
                            "code" => 'yellow',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "Желтый"
                        ],
                        [
                            "code" => 'blue',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "Голубой"
                        ],
                        [
                            "code" => 'white',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "Белый"
                        ]
                    ]
                ],
                [
                    "code" => 'insurance',
                    "name" => "Страховка",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "values" => [
                        [
                            "code" => 'osago',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "ОСАГО"
                        ]
                    ]
                ]
            ]
        ],
        [
            "id" => 3,
            "singleAttributes" => [
                [
                    "code" => 'name',
                    "name" => "Название",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "Шикарный Volkswagen Polo"
                    ]
                ],
                [
                    "code" => 'type',
                    "name" => "Тип элемента",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "element"
                    ]
                ],
                [
                    "code" => 'brand',
                    "name" => "Марка машины",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => 'volkswagen',
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "VW"
                    ]
                ],
                [
                    "code" => 'model',
                    "name" => "Модель машины",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => 'polo',
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "Polo"
                    ]
                ],
                [
                    "code" => 'year',
                    "name" => "Год выпуска",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "2015"
                    ]
                ],
                [
                    "code" => 'price',
                    "name" => "Цена",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => '500000'
                    ]
                ]
            ],
            "multipleAttributes" => [
                [
                    "code" => 'color',
                    "name" => "Цвет",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "values" => [
                        [
                            "code" => 'white',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "Белый"
                        ]
                    ]
                ],
                [
                    "code" => 'insurance',
                    "name" => "Страховка",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "values" => [
                        [
                            "code" => 'kasko',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "КАСКО"
                        ]
                    ]
                ]
            ]
        ],
        [
            "id" => 4,
            "singleAttributes" => [
                [
                    "code" => 'name',
                    "name" => "Название",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "Примиум Volkswagen Polo"
                    ]
                ],
                [
                    "code" => 'type',
                    "name" => "Тип элемента",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "element"
                    ]
                ],
                [
                    "code" => 'brand',
                    "name" => "Марка машины",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => 'volkswagen',
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "volkswagen"
                    ]
                ],
                [
                    "code" => 'model',
                    "name" => "Модель машины",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => 'polo',
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "Polo"
                    ]
                ],
                [
                    "code" => 'year',
                    "name" => "Год выпуска",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => "2020"
                    ]
                ],
                [
                    "code" => 'price',
                    "name" => "Цена",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "value" => [
                        "code" => null,
                        "description" => null,
                        "img" => null,
                        "additionalInfo" => null,
                        "value" => '700000'
                    ]
                ]
            ],
            "multipleAttributes" => [
                [
                    "code" => 'color',
                    "name" => "Цвет",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "values" => [
                        [
                            "code" => 'yellow',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "Желтый"
                        ]
                    ]
                ],
                [
                    "code" => 'insurance',
                    "name" => "Страховка",
                    "description" => null,
                    "img" => null,
                    "additionalInfo" => null,
                    "values" => [
                        [
                            "code" => 'kasko',
                            "description" => null,
                            "img" => null,
                            "additionalInfo" => null,
                            "value" => "КАСКО"
                        ]
                    ]
                ]
            ]
        ],
    ]
];