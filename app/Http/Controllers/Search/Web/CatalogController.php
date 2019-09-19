<?php

namespace App\Http\Controllers\Search\Web;

use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
   public function index()
   {
       $apiInstance = new \SwaggerSearch\Api\CatalogApi(
       // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
       // This is optional, `GuzzleHttp\Client` will be used as default.
           new \GuzzleHttp\Client()
       );
       $apiInstance->getConfig()->setHost('http://10.101.2.10/api');
       $engine = 'elasticsearch'; //  | Код поискового движка
       $index = 'auto'; //  | Код индекса
       $request = new \SwaggerSearch\Model\Request([]); // \SwaggerSearch\Model\Request |
       $page = 1; // int | Номер запрашиваемой страницы результата
       $page_size = 20; // int | Количество возвращаемых объектов на странице

       try {
           $result = $apiInstance->engineIndexIndexCatalogSearchPost($engine, $index, $request, $page, $page_size);
           dump($result);
       } catch (\Exception $e) {
           echo 'Exception when calling CatalogApi->engineIndexIndexCatalogSearchPost: ', $e->getMessage(), PHP_EOL;
       }
       return view('demo.catalog');
   }
}