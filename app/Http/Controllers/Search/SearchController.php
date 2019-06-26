<?php

namespace App\Http\Controllers\Search;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CatalogListRequest;
use App\Search\Query\Request\Elasticsearch;
use SwaggerUnAuth\Model\Filter;

class SearchController extends Controller
{
    /**
     * @param CatalogListRequest $request
     * @return \SwaggerUnAuth\Model\ListItem[]
     * @throws ApiException
     */
    public function catalogList(CatalogListRequest $request)
    {
        /**
         * @var Filter $filter
         */
        $filter = $request->getValid('filter');
        switch ($request->get('engine')) {
            case 'elasticsearch': { // TODO: ИИ
                $elasticSearch = new Elasticsearch();
                $items = $elasticSearch->postCatalogList($filter);
                break;
            }
            default: {
                throw new ApiException('EngineNotFound', sprintf('engine %s not found', $request->get('engine')), 404);
            }
        }
        return $items;
    }
}