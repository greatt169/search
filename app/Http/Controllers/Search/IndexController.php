<?php
namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeleteDocumentRequest;
use App\Http\Requests\Api\UpdateRequest;
use App\Http\Requests\Api\ReindexRequest;
use App\Exceptions\ApiException;
use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;
use App\Search\Index\Manager\Elasticsearch as ElasticsearchManager;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
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
        $indexer = new ElasticsearchManager(
            new ElasticsearchSource('auto', '/var/www/public/data-update.json'),
            new ElasticsearchEntity()
        );

        $indexer->update();
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

    /**
     * @param UpdateRequest $request
     * @return
     * @throws ApiException
     * @throws BindingResolutionException
     */
    public function update(UpdateRequest $request)
    {
        /**
         * @var Engine $engine
         */
        $engine = $request->getValid('engine');
        $index = $request->get('index');
        $dataLink = $request->getValid('dataLink');

        /**
         * @var RequestEngineInterface $engineRequest
         */
        $engineRequest = RequestEngine::getInstance($engine->getName(), $index);
        return $engineRequest->update($index, $dataLink);
    }

    /**
     * @param DeleteDocumentRequest $request
     * @return mixed
     * @throws ApiException
     * @throws BindingResolutionException
     */
    public function delete(DeleteDocumentRequest $request)
    {

        $index = $request->route('index');
        $engine = $request->route('engine');
        $id = $request->route('doc_id');

        /**
         * @var RequestEngineInterface $engineRequest
         */
        $engineRequest = RequestEngine::getInstance($engine, $index);
        return $engineRequest->deleteElement($id);
    }
}