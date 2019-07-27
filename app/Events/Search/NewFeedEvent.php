<?php

namespace App\Events\Search;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewFeedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $dataLink;
    /**
     * @var null
     */
    private $settingsLink;

    /**
     * Create a new event instance.
     *
     * @param $dataLink
     * @param null $settingsLink
     */
    public function __construct($dataLink, $settingsLink = null)
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