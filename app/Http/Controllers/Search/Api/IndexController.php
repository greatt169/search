<?php

namespace App\Http\Controllers\Search\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeleteDocumentRequest;
use App\Http\Requests\Api\UpdateRequest;
use App\Http\Requests\Api\ReindexRequest;
use App\Search\Query\Interfaces\RequestEngineInterface;
use App\Search\Query\Request\Engine as RequestEngine;
use App\Search\UseCases\Errors\Error;
use SwaggerSearch\Model\ActionSuccessResult;
use SwaggerSearch\Model\ReindexResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class IndexController extends Controller
{
    /**
     * @param ReindexRequest $request
     * @param Error $errorService
     * @return ReindexResponse|Response
     */
    public function reindex(ReindexRequest $request, Error $errorService)
    {
        try {
            $engine = $request->route('engine');
            $index = $request->route('index');

            /**
             * @var RequestEngineInterface $engineRequest
             */
            $engineRequest = RequestEngine::getInstance($engine, $index);
            $dataLink = $request->input('dataLink');
            $settingsLink = $request->input('settingsLink');
            return $engineRequest->reindex($index, $dataLink, $settingsLink);
        } catch (Throwable $exception) {
            $error = $errorService->getError($exception);
            return new Response($error->__toString(), $error->getHttpCode());
        }
    }

    /**
     * @param UpdateRequest $request
     * @param Error $errorService
     * @return ReindexResponse|Response
     */
    public function update(UpdateRequest $request, Error $errorService)
    {
        try {
            $engine = $request->route('engine');
            $index = $request->route('index');
            $dataLink = $request->input('dataLink');

            /**
             * @var RequestEngineInterface $engineRequest
             */
            $engineRequest = RequestEngine::getInstance($engine, $index);
            return $engineRequest->update($index, $dataLink);
        } catch (Throwable $exception) {
            $error = $errorService->getError($exception);
            return new Response($error->__toString(), $error->getHttpCode());
        }
    }

    /**
     * @param DeleteDocumentRequest $request
     * @param Error $errorService
     * @return ActionSuccessResult|Response
     */
    public function delete(DeleteDocumentRequest $request, Error $errorService)
    {
        try {
            $index = $request->route('index');
            $engine = $request->route('engine');
            $id = $request->route('doc_id');

            /**
             * @var RequestEngineInterface $engineRequest
             */
            $engineRequest = RequestEngine::getInstance($engine, $index);
            return $engineRequest->deleteElement($id);
        } catch (Throwable $exception) {
            $error = $errorService->getError($exception);
            return new Response($error->__toString(), $error->getHttpCode());
        }
    }
}