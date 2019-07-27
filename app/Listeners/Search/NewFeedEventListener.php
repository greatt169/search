<?php

namespace App\Listeners\Search;

use App\Events\Search\NewFeedEvent;
use App\Jobs\Search\IndexFeed;

class NewFeedEventListener
{
    /**
     * Handle the event.
     *
     * @param  NewFeedEvent  $event
     * @return void
     */
    public function handle(NewFeedEvent $event)
    {
        IndexFeed::dispatch($event);
    }
}