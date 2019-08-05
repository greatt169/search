<?php

namespace App\Http\Requests\Api;

use SwaggerSearch\Model\UpdateParams;

class UpdateRequest extends ReindexRequest
{
    protected function getSwaggerModelParams() {
        return UpdateParams::class;
    }
}