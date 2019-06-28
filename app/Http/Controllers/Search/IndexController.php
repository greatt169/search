<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;

use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;
use App\Search\Index\Manager\Elasticsearch;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
use SwaggerUnAuth\Model\ListItem;
use SwaggerUnAuth\ObjectSerializer;


class IndexController extends Controller
{
    public function index()
    {
        $d = [
            [
                "id" => 5,
                "attributes" => [
                    "single" => [
                        [
                            "id" => 764,
                            "name" => "Тип коробки передач",
                            "description" => "Произвольное описание параметра",
                            "img" => "https=>//cdn.fast.ru/files/1sdasd88dasdsad.jpg",
                            "additional_info" => "string",
                            "value" => [
                                "id" => 44,
                                "description" => "Произвольное описание параметра",
                                "img" => "https=>//cdn.fast.ru/files/1sdasd88dasdsad.jpg",
                                "additional_info" => "string",
                                "value" => "АКПП"
                            ]
                        ]
                    ],
                    "multiple" => [
                        [
                            "id" => 764,
                            "name" => "Тип коробки передач",
                            "description" => "Произвольное описание параметра",
                            "img" => "https=>//cdn.fast.ru/files/1sdasd88dasdsad.jpg",
                            "additional_info" => "string",
                            "values" => [
                                [
                                    "id" => 44,
                                    "description" => "Произвольное описание параметра",
                                    "img" => "https=>//cdn.fast.ru/files/1sdasd88dasdsad.jpg",
                                    "additional_info" => "string",
                                    "value" => "АКПП"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        echo '<pre>';
        print_r(ObjectSerializer::deserialize(json_decode(json_encode($d)), ListItem::class, null));
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
