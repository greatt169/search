<?php

namespace App\Listeners\Search;

use App\Events\Search\NewFeedUpdateEvent;
use App\Jobs\Search\FeedUpdate;

class NewFeedUpdateEventListener
{
    /**
     * Handle the event.
     *
     * @param  NewFeedUpdateEvent $event
     * @return void
     */
    public function handle(NewFeedUpdateEvent $event)
    {
        FeedUpdate::dispatch($event);
    }
}