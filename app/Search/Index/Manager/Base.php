<?php

namespace App\Search\Index\Manager;

use App\Helpers\Timer;
use App\Search\Index\Interfaces\ManagerInterface;
use App\Search\Index\Interfaces\SourceInterface;
use App\Search\Index\Interfaces\TimerInterface;

abstract class Base implements ManagerInterface
{
    /**
     * @var SourceInterface $source
     */
    protected $source;

    /**
     * @var TimerInterface|null $timer
     */
    protected $timer;

    /**
     * Base constructor.
     * @param SourceInterface $source
     * @param TimerInterface|null $timer
     */
    public function __construct(SourceInterface $source, TimerInterface $timer = null)
    {
        if($timer === null) {
            $this->timer = new Timer();
        } else {
            $this->timer = $timer;
        }
        $this->source = $source;
    }

    /**
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }
}