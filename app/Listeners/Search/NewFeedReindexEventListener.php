<?php

namespace App\Listeners\Search;

use App\Events\Search\NewFeedReindexEvent;
use App\Jobs\Search\FeedReindex;

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
        FeedReindex::dispatch($event);
    }
}