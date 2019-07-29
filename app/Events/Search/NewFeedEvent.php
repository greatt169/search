<?php

namespace App\Events\Search;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewFeedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $id;

    protected $dataLink;

    protected $settingsLink;

    /**
     * Create a new event instance.
     *
     * @param $id
     * @param string $dataLink
     * @param null | string $settingsLink
     */
    public function __construct($id, string $dataLink, string $settingsLink = null)
    {
        $this->id = $id;
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}