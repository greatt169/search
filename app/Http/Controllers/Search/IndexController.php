<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;

use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;
use App\Search\Index\Manager\Elasticsearch;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
use SwaggerUnAuth\Model\ListItem;
use SwaggerUnAuth\Model\ListItems;
use SwaggerUnAuth\ObjectSerializer;


class IndexController extends Controller
{
    public function index()
    {
        $d = [
            "items" => [
                [
                    "id" => 1,
                    "attributes" => [
                        "single" => [
                            "name" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => "Внедорожник BMW-X5"
                            ],
                            "brand" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => "BMV"
                            ],
                            "model" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => "X5"
                            ],
                            "year" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => 2008
                            ],
                            "price" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => 10000
                            ]
                        ],
                        "multiple" => [
                            "color" => [
                                "values" => [
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => 'red'
                                    ],
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => 'blue'
                                    ],
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => 'black'
                                    ]
                                ]
                            ],
                            "insurance" => [
                                "values" => [
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => "kasko"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "id" => 2,
                    "attributes" => [
                        "single" => [
                            "name" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => "Старая Lada Granda"
                            ],
                            "brand" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => "Lada"
                            ],
                            "model" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => "Granda"
                            ],
                            "year" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => 1997
                            ],
                            "price" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => 1000
                            ]
                        ],
                        "multiple" => [
                            "color" => [
                                "values" => [
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => 'white'
                                    ]
                                ]
                            ],
                            "insurance" => [
                                "values" => [
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => "kasko"
                                    ],
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => "osago"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "id" => 3,
                    "attributes" => [
                        "single" => [
                            "name" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => "Управляемый Volkswagen Polo"
                            ],
                            "brand" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => "Volkswagen"
                            ],
                            "model" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => "Polo"
                            ],
                            "year" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => 2015
                            ],
                            "price" => [
                                "description" => null,
                                "img" => null,
                                "additionalInfo" => null,
                                "value" => 4000
                            ]
                        ],
                        "multiple" => [
                            "color" => [
                                "values" => [
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => 'gray'
                                    ],
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => 'white'
                                    ]
                                ]
                            ],
                            "insurance" => [
                                "values" => [
                                    [
                                        "description" => null,
                                        "img" => null,
                                        "additionalInfo" => null,
                                        "value" => "osago"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        echo '<pre>';
        print_r(ObjectSerializer::deserialize(json_decode(json_encode($d)), ListItems::class, null));
        echo '</pre>';


        $indexer = new Elasticsearch(
            new ElasticsearchSource(),
            new ElasticsearchEntity()
        );
        dump($indexer->getIndex());
        $client = $indexer->getClient();
        $params = [
            'index' => $indexer->getIndex(),
            'type' => $indexer->getType(),
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'match' => [
                                    'colors' => 'black'
                                ]
                            ],
                            [
                                'match' => [
                                    'colors' => 'white'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $results = $client->search($params);
        dump($results);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function reindex()
    {

    }
}
