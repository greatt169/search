<?php

namespace App\Events\Search;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewFeedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $dataLink;
    /**
     * @var null
     */
    protected $settingsLink;

    /**
     * Create a new event instance.
     *
     * @param string$dataLink
     * @param null | string $settingsLink
     */
    public function __construct(string $dataLink, string $settingsLink = null)
    {
        //
        $this->dataLink = $dataLink;
        $this->settingsLink = $settingsLink;
    }

    /**
     * @return mixed
     */
    public function getDataLink()
    {
        return $this->dataLink;
    }

    /**
     * @return null
     */
    public function getSettingsLink()
    {
        return $this->settingsLink;
    }
}