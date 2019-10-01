<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function index()
    {
        $redis = Redis::connection();
        $redis->hset('auto', 'color',
            \GuzzleHttp\json_encode(
                [
                    'name' => 'Цвет',
                    'values' => [
                        [
                            'code' => 'red',
                            'name'=> 'красный'
                        ],
                        [
                            'code' => 'white',
                            'name'=> 'белый'
                        ]
                    ]
                ]
            )
        );


        $redis->hset('auto', 'brand',
            \GuzzleHttp\json_encode(
                [
                    'name' => 'Производитель',
                    'values' => [
                        [
                            'code' => 'bmw',
                            'name'=> 'Бэха'
                        ],
                        [
                            'code' => 'audi',
                            'name'=> 'Аудюха'
                        ]
                    ]
                ]
            )
        );

        $redis->hset('auto', 'model',
            \GuzzleHttp\json_encode(
                [
                    'name' => 'Модель авто',
                    'values' => [
                        [
                            'code' => 'granta',
                            'name'=> 'Грантец'
                        ],
                        [
                            'code' => 'vesta',
                            'name'=> 'Весточка!'
                        ]
                    ]
                ]
            )
        );

        dump($redis->hmget('auto', ['model', 'color']));

    }

    public function jsonPack($filePath = '/var/www/public/data.json')
    {
        $content = file_get_contents($filePath);
        file_put_contents($filePath, json_encode(json_decode($content, true)));
    }
}