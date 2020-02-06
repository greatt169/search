<?php

namespace App\Jobs\Search;

use App\Events\Search\NewFeedUpdateEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FeedUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $event;

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        $tags = [
            'update-data',
            'engine: ' . $this->event->getEngine(),
            'id: ' . $this->event->getId(),
            'index ' . $this->event->getIndexer()->getSource()->getIndexName(),
            'dataLink: ' . $this->event->getIndexer()->getSource()->getDataLink()
        ];
        return $tags;
    }


    public function __construct(NewFeedUpdateEvent $event)
    {
        $this->event = $event;
    }


    /**
     * Execute the job.
     *
     * @return void
     * @throws \App\Exceptions\ApiException
     */
    public function handle()
    {
        $indexer = $this->event->getIndexer();
        $indexer->update();
    }
}
