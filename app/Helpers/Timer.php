<?php

namespace App\Helpers;

class Timer
{
    /**
     * @var object
     */

    private static $_instance;

    /**
     * @var array
     */

    private $timers = [];

    /**
     * @var array
     */

    private $intervals = [];

    public static function getInstance()
    {
        if (is_null(self::$_instance))
            self::$_instance = new Timer();
        return self::$_instance;
    }

    /**
     * @param string $timerName
     */

    public function start($timerName)
    {
        $this->timers[$timerName] = microtime(true);
    }

    /**
     * @param string $timerName
     */

    public function end($timerName)
    {
        $this->intervals[$timerName][] = microtime(true) - $this->timers[$timerName];
    }

    /**
     * @param $timerName
     * @return mixed
     */

    public function getInterval($timerName)
    {
        return microtime(true) - $this->timers[$timerName];
    }

    /**
     * @return array
     */

    public function getIntervals()
    {
        return $this->intervals;
    }

     /*
     * @return array
     */

    public function getIntervalsAvg()
    {
        $avgArray = Array();
        foreach ($this->intervals as $name => $data) {
            $c = count($data);
            $avgArray[$name . ' x ' . $c] = array_sum($data) / $c;
        }
        return $avgArray;
    }

    /**
     * @return array
     */

    public function getIntervalsSum()
    {
        $sumArray = Array();
        foreach ($this->intervals as $name => $data) {
            $c = count($data);
            $sumArray[$name . ' x ' . $c] = array_sum($data);
        }
        return $sumArray;
    }
}