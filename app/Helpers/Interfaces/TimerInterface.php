<?php

namespace App\Helpers\Interfaces;
/**
 * Interface TimerInterface
 * @package App\Helpers\Interfaces
 */
interface TimerInterface
{
    public function start($timerName);

    /**
     * @param string $timerName
     */

    public function end($timerName);

    /**
     * @param $timerName
     * @return mixed
     */

    public function getInterval($timerName);

    /**
     * @return array
     */

    public function getIntervals();

    /**
     * @return array
     */
    public function getIntervalsAvg();

    /**
     * @return array
     */

    public function getIntervalsSum();
}