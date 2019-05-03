<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Search\Entity\Index\Document;
use App\Search\Entity\Index\DocumentAttribute;

class IndexController extends Controller
{
    public function index()
    {
        $data = include_once('data.php');
        $objects = [];
        foreach ($data as $dataItem) {
            $dto = new Document();
            $dto->id = $dataItem['id'];

            $color = new DocumentAttribute();
            $color->code = 'colors';
            $color->multiple = true;
            $color->type = 'string';
            $color->value = $dataItem['colors'];

            $price = new DocumentAttribute();
            $price->code = 'price';
            $price->multiple = false;
            $price->type = 'float';
            $price->value = $dataItem['price'];

            $dto->attributes[] = $color;
            $dto->attributes[] = $price;

            $objects[] = $dto;
        }
        $objects = collect($objects);
        dd($objects);
    }
}
