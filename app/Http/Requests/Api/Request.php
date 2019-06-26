<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * @var array
     */
    protected $validData;

    public function setValid($key, $value) {
        $this->validData[$key] = $value;
    }

    public function getValid($key) {
        return $this->validData[$key];
    }

    protected function getDeserializeData()
    {
        $data = json_decode(json_encode($this->input()));
        return $data;
    }
}