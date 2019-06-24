<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SwaggerUnAuth\Model\CatalogListFilter;
use SwaggerUnAuth\ObjectSerializer;

class SearchController extends Controller
{
    public function catalogList(Request $request)
    {
        $filter = ObjectSerializer::deserialize($request->toArray(), '\SwaggerUnAuth\Model\CatalogListFilter');
        return $filter;
    }
}
