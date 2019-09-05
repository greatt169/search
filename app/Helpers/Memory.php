<?php

namespace App\Helpers;

use App\Helpers\Interfaces\MemoryInterface;

class Memory implements MemoryInterface
{
    public function calculateUsedMemory()
    {
        $formatBytes = function($bytes, $precision = 2) {
            $units = array("b", "kb", "mb", "gb", "tb");

            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            $bytes /= (1 << (10 * $pow));

            return round($bytes, $precision) . " " . $units[$pow];
        };
        $usedMemory = $formatBytes(memory_get_peak_usage());
        return $usedMemory;
    }
}