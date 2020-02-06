<?php

namespace App\Demo\Helpers;

class References
{
    public function getTree()
    {
        $dataJson = file_get_contents('references.json');
        $data = json_decode($dataJson, true);
        return $data;
    }
}