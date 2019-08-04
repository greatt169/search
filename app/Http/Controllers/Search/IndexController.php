<?php
namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReindexRequest;
use App\Exceptions\ApiException;
use App\Search\Query\Interfaces\RequestEngineInterface;
use App\Search\Query\Request\Engine as RequestEngine;
use SwaggerSearch\Model\Engine;
use \Illuminate\Contracts\Container\BindingResolutionException;
use SwaggerSearch\Model\ReindexResponse;

class IndexController extends Controller
{
    /**
     * @throws \Exception
     */
    public function demo()
    {
        echo date('d.m.Y H:i:S');
    }

    /**
     * @param ReindexRequest $request
     * @return ReindexResponse
     * @throws ApiException
     * @throws BindingResolutionException
     */
    public function reindex(ReindexRequest $request)
    {
        /**
         * @var Engine $engine
         */
        $engine = $request->getValid('engine');
        $index = $request->get('index');

        /**
         * @var RequestEngineInterface $engineRequest
         */
        $engineRequest = RequestEngine::getInstance($engine->getName(), $index);
        $dataLink = $request->getValid('dataLink');
        $settingsLink = $request->get('settingsLink');
        return $engineRequest->reindex($index, $dataLink, $settingsLink);
    }

    public function index(IndexRequest $request)
    {

    }
}