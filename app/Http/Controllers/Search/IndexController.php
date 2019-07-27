<?php

namespace App\Http\Controllers\Search;

use App\Events\Search\NewFeedEvent;
use App\Http\Controllers\Controller;

use App\Http\Requests\Api\ReindexRequest;
use App\Exceptions\ApiException;
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
        //event(new NewFeedEvent('/var/www/public/data.json', null));
    }

    /**
     * @param ReindexRequest $request
     * @return string
     * @throws ApiException
     */
    public function reindex(ReindexRequest $request)
    {
        $dataLink = $request->getValid('dataLink');
        $settingsLink = $request->get('settingsLink');
        event(new NewFeedEvent($dataLink, $settingsLink));
        return 'Job in queue';
    }
}