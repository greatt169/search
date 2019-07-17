<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\ReindexRequest;
use App\Search\Index\Listeners\SourceListener;
use App\Search\Index\Manager\Elasticsearch;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;
use Exception;


class IndexController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index()
    {
        ini_set('max_execution_time', 900);
        ini_set('memory_limit', '-1');
        $sourceLink = 'http://10.101.2.10/data_test.json';

        $data = include  '/var/www/public/data_full.php';
        $testItems = [];
        for($i = 0; $i < 100; $i++) {
            $item = $data['items'][rand(0,3)];
            $item['id'] = $i+1;
            $testItems[] = $item;
        }
        $data['items'] = $testItems;

        $data = json_encode($data);
        $file = '/var/www/public/data_test.json';
        file_put_contents($file, $data);


        /*$source = new ElasticsearchSource($sourceLink);
        $data = $source->getElementsForIndexing();
        dump($data);



        $listener = new SourceListener(function ($item): void {
            $formatBytes = function($bytes, $precision = 2) {
                $units = array("b", "kb", "mb", "gb", "tb");

                $bytes = max($bytes, 0);
                $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                $pow = min($pow, count($units) - 1);

                $bytes /= (1 << (10 * $pow));

                return round($bytes, $precision) . " " . $units[$pow];
            };


            //dump($item);
            //print $formatBytes(memory_get_peak_usage()) . echo <>;
        });

        $stream = fopen($sourceLink, 'r');
        try {
            $parser = new \JsonStreamingParser\Parser($stream, $listener);
            $parser->parse();
            fclose($stream);
        } catch (Exception $e) {
            fclose($stream);
            throw $e;
        }







        /*$data = $source->getElementsForIndexing();
        dump($data);*/
    }

    /**
     * @param ReindexRequest $request
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function reindex(ReindexRequest $request)
    {
        $sourceLink = $request->getValid('link');
        $indexer = new Elasticsearch(
            new ElasticsearchSource($sourceLink),
            new ElasticsearchEntity()
        );
        $indexer->reindex();
        $displayResultMessages = $indexer->getDisplayResultMessages();
        return $displayResultMessages;
    }
}
