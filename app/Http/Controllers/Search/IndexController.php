<?php
namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReindexRequest;
use App\Exceptions\ApiException;
use App\Search\Query\Interfaces\RequestEngineInterface;
use App\Search\Query\Request\Engine as RequestEngine;
use SwaggerSearch\Model\Engine;
use SwaggerSearch\Model\ListItemAttributeValue;

class IndexController extends Controller
{
    /**
     * @param ListItemAttributeValue $attributeValue
     * @return string
     */
    protected function getAttributeVal($attributeValue) {
        $val = $attributeValue->getCode();
        if($val === null) {
            $val = $attributeValue->getValue();
        }
        return $val;
    }

    /**
     * @throws \Exception
     */
    public function index()
    {
        echo date('d.m.Y H:i:S');
    }

    /**
     * @param ReindexRequest $request
     * @return string
     * @throws ApiException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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
}