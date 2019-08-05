<?php

namespace App\Listeners\Search;

use App\Events\Search\NewFeedReindexEvent;
use App\Jobs\Search\IndexFeed;

class NewFeedReindexEventListener
{
    /**
     * Handle the event.
     *
     * @param  NewFeedReindexEvent  $event
     * @return void
     */
    public function handle(NewFeedReindexEvent $event)
    {
        IndexFeed::dispatch($event);
    }
}