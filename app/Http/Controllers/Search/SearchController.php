<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SwaggerUnAuth\ObjectSerializer;

class SearchController extends Controller
{
    public function catalogList(Request $request)
    {
        $requestParam = json_decode(json_encode($request->all()));
        /**
         * @var \SwaggerUnAuth\Model\Filter $filter
         */
        $filter = ObjectSerializer::deserialize($requestParam, '\SwaggerUnAuth\Model\Filter', null);

        return $filter;
    }
}