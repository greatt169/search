<?php

namespace App\Search\Index\Interfaces;

use App\Helpers\Interfaces\MemoryInterface;
use App\Search\Entity\Interfaces\EntityInterface;
use App\Helpers\Interfaces\TimerInterface;

interface ManagerInterface
{
    public function createIndex();

    public function dropIndex();

    public function setMapping();

    public function indexAll();

    public function removeAll();

    public function indexElement($id);

    public function __construct(SourceInterface $source, EntityInterface $entity, TimerInterface $timer = null, MemoryInterface $memory = null);
}