<?php

namespace App\Search\Index\Manager;

use App\Helpers\Timer;
use App\Helpers\Interfaces\TimerInterface;

use App\Search\Entity\Interfaces\EntityInterface;
use App\Search\Index\Interfaces\ManagerInterface;
use App\Search\Index\Interfaces\SourceInterface;

abstract class Base implements ManagerInterface
{
    /**
     * @var SourceInterface $source
     */
    protected $source;

    /**
     * @var EntityInterface $entity
     */
    protected $entity;

    /**
     * @var TimerInterface|null $timer
     */
    protected $timer;

    /**
     * @var
     */
    protected $startTime;

    /**
     * Base constructor.
     * @param SourceInterface $source
     * @param EntityInterface $entity
     * @param TimerInterface|null $timer
     */
    public function __construct(SourceInterface $source, EntityInterface $entity, TimerInterface $timer = null)
    {
        if($timer === null) {
            $this->timer = new Timer();
        } else {
            $this->timer = $timer;
        }
        $this->source = $source;
        $this->entity = $entity;
        $this->startTime = time();
    }

    /**
     * @return SourceInterface
     */
    public function getSource()
    {
        return $this->source;
    }
}