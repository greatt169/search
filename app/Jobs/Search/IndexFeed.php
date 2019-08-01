<?php

namespace App\Jobs\Search;

use App\Events\Search\NewFeedEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class IndexFeed implements ShouldQueue
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
            'full-reindex',
            'engine: ' . $this->event->getEngine(),
            'id: ' . $this->event->getId(),
            'index ' . $this->event->getIndex(),
            'dataLink: ' . $this->event->getDataLink()
        ];
        $settingsLink = $this->event->getSettingsLink();
        if($settingsLink) {
            $tags[] = '--settingsLink: ' . $settingsLink;
        }
        return $tags;
    }


    public function __construct(NewFeedEvent $event)
    {
        $this->event = $event;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $index = $this->event->getIndex();
        $dataLink = $this->event->getDataLink();
        $settingsLink = $this->event->getSettingsLink();
        $commandSignature = sprintf('search:%s:reindex', $this->event->getEngine());

        Artisan::call($commandSignature, [
            'index' => $index,
            'data' => $dataLink,
            '--settings' => $settingsLink
        ]);
    }
}
