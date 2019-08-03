<?php

namespace App\Events\Search;

use App\Search\Index\Interfaces\ManagerInterface;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewFeedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $id;

    protected $dataLink;

    protected $settingsLink;

    protected $engine;

    /**
     * @var string
     */
    private $index;
    /**
     * @var ManagerInterface
     */
    private $indexer;

    /**
     * Create a new event instance.
     *
     * @param $id
     * @param string $engine
     * @param ManagerInterface $indexer
     */
    public function __construct($id, string $engine, ManagerInterface $indexer)
    {
        $this->id = $id;
        $this->engine = $engine;
        $this->indexer = $indexer;
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

    /**
     * @return string
     */
    public function getEngine(): string
    {
        return $this->engine;
    }

    /**
     * @return ManagerInterface
     */
    public function getIndexer(): ManagerInterface
    {
        return $this->indexer;
    }
}