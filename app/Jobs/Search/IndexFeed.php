<?php

namespace App\Jobs\Search;

use App\Events\Search\NewFeedEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;

class IndexFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param NewFeedEvent $event
     * @return void
     */
    public function handle(NewFeedEvent $event)
    {
        $dataLink = $event->getDataLink();
        $settingsLink = $event->getSettingsLink();
        Artisan::call('search:reindex', [
            'data' => $dataLink, '--settings' => $settingsLink
        ]);
    }
}
