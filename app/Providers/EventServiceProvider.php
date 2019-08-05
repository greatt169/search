<?php

namespace App\Providers;

use App\Events\Search\NewFeedReindexEvent;
use App\Events\Search\NewFeedUpdateEvent;
use App\Listeners\Search\NewFeedReindexEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NewFeedReindexEvent::class => [
            NewFeedReindexEventListener::class,
        ],
        NewFeedUpdateEvent::class => [
            NewFeedReindexEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
