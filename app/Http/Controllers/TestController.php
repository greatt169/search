<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Search\Index\Listeners\SourceListener;
use App\Search\Index\Source\Elasticsearch;
use App\Search\UseCases\Errors\Error;
use JsonStreamingParser\Parser;

class TestController extends Controller
{
   public function index()
   {
       $listener = new SourceListener(function ($rawItems) {
           $source = new Elasticsearch('auto', '/var/www/public/data.json', '/var/www/public/settings.json');
           $items = $source->getElementsForIndexing($rawItems);
           $params = ['body' => []];
           foreach ($items as $index => $document) {
               $i = $index + 1;
               $arDocAttributes = [];
               foreach ($document['attributes'] as $attributeCode => $attributeValue) {
                   $arDocAttributes[$attributeCode] = $attributeValue;
               }
               $arDocAttributes['raw_data'] = $document['raw_data'];
               $arDocAttributes['search_data'] = $document['search_data'];
               $params['body'][] = [
                   'index' => [
                       '_id' => $document['id']
                   ]
               ];
               $params['body'][] = $arDocAttributes;
               dump($params);
           }
       });

       $source = new Elasticsearch('auto', '/var/www/public/data.json', '/var/www/public/settings.json');
       $listener->setBatchSize(100);
       $stream = fopen($source->getDataLink(), 'r');
       try {
           $parser = new Parser($stream, $listener);
           $parser->parse();
           $total = $listener->getTotal();
           fclose($stream);
       } catch (\Exception $e) {
           dump($e);
           fclose($stream);
       }
       return 'test 1';
   }

   public function jsonPack($filePath = '/var/www/public/data.json')
   {
       $content = file_get_contents($filePath);
       file_put_contents($filePath, json_encode(json_decode($content, true)));
   }
}