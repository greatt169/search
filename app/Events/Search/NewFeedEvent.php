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
     * @var string
     */
    private $index;

    /**
     * Create a new event instance.
     *
     * @param $id
     * @param string $index
     * @param string $dataLink
     * @param null | string $settingsLink
     */
    public function __construct($id, string $index, string $dataLink, string $settingsLink = null)
    {
        $this->id = $id;
        $this->dataLink = $dataLink;
        $this->settingsLink = $settingsLink;
        $this->index = $index;
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

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }
}