<?php

return [
    "mapping" => [
        "name" => [
            "type" => "keyword",
        ],"model" => [
            "type" => "keyword",
        ],"color" => [
            "type" => "keyword",
        ],"insurance" => [
            "type" => "keyword",
        ],"brand" => [
            "type" => "keyword",
        ],"price" => [
            "type" => "float",
        ],"year" => [
            "type" => "integer",
        ]
    ],
    "items" => [
        [
            "id" => 1,
            "singleAttributes" => [
                "name" => "Внедорожник BMW-X5",
                "brand" => "BMV",
                "model" =>  "X5",
                "year" => 2008,
                "price" => 10000
            ],
            "multipleAttributes" => [
                "color" => [
                    "red",
                    "blue",
                    "black"
                ],
                "insurance" => [
                    "kasko"
                ]
            ]
        ],
        [
            "id" => 2,
            "singleAttributes" => [
                "name" => "Старая Lada Granta",
                "brand" => "Lada",
                "model" =>  "Granta",
                "year" => 1998,
                "price" => 100
            ],
            "multipleAttributes" => [
                "color" => [
                    "white",
                    "blue",
                ],
                "insurance" => [
                    "osago",
                    "kasko",
                ]
            ]
        ],
        [
            "id" => 3,
            "singleAttributes" => [
                "name" => "Крутой Volkswagen Polo",
                "brand" => "Volkswagen",
                "model" =>  "Polo",
                "year" => 2015,
                "price" => 4300
            ],
            "multipleAttributes" => [
                "color" => [
                    "white"
                ],
                "insurance" => [
                    "osago"
                ]
            ]
        ],
        [
            "id" => 4,
            "singleAttributes" => [
                "name" => "Свежий Volkswagen Polo",
                "brand" => "Volkswagen",
                "model" =>  "Polo",
                "year" => 2019,
                "price" => 6000
            ],
            "multipleAttributes" => [
                "color" => [
                    "grey"
                ],
                "insurance" => [
                    "kasko"
                ]
            ]
        ]
    ]
];