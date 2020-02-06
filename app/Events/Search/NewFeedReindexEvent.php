<?php

namespace App\Events\Search;

use App\Search\Index\Interfaces\ManagerInterface;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewFeedReindexEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $id;

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
    public function getId()
    {
        return $this->id;
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