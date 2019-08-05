<?php

namespace App\Providers;

use App\Helpers\Interfaces\SerializerInterface;
use App\Helpers\Serializer;
use App\Http\Requests\Api\CatalogListRequest;
use App\Http\Requests\Api\ReindexRequest;
use App\Http\Requests\Api\UpdateRequest;
use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;
use App\Search\Entity\Interfaces\EntityInterface;
use App\Search\Query\Request\Elasticsearch;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(Elasticsearch::class)
            ->needs(EntityInterface::class)
            ->give(ElasticsearchEntity::class);

        $this->app->when(CatalogListRequest::class)
            ->needs(SerializerInterface::class)
            ->give(Serializer::class);

        $this->app->when(ReindexRequest::class)
            ->needs(SerializerInterface::class)
            ->give(Serializer::class);

        $this->app->when(UpdateRequest::class)
            ->needs(SerializerInterface::class)
            ->give(Serializer::class);
    }
}