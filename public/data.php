<?php
/*return [
    [
        'id' => 1,
        'brand' => 'BMW',
        'model' => 'X5',
        'model_logo' => '',
        'year' => 2005,
        'colors' => [
            'red',
            'blue'
        ],
        'price' => 10000,
    ],
    [
        'id' => 2,
        'brand' => 'Lada',
        'model' => 'Granta',
        'model_logo' => '',
        'colors' => ['white'],
        'year' => 2017,
        'price' => 4000,
    ],
    [
        'id' => 3,
        'brand' => 'Volkswagen',
        'model' => 'Polo',
        'model_logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTtyxombOfRXUtneAUbChtZBq1K3v_bxbV4VFxfps2BYBYQsjK9',
        'colors' => [
            'blue',
            'black'
        ],
        'year' => 2015,
        'price' => 50000,
    ],
    [
        'id' => 4,
        'brand' => 'Volkswagen',
        'model' => 'Polo',
        'model_logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTtyxombOfRXUtneAUbChtZBq1K3v_bxbV4VFxfps2BYBYQsjK9',
        'colors' => [
            'blue',
        ],
        'year' => 2013,
        'price' => 10000,
    ]
];*/

return json_encode(
    [
        [
            "items" => [
                [
                    "id" => 5,
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
                ]
            ]
        ]
    ]
);