<?php

declare(strict_types=1);

namespace Hbp\Import;

class PerformanceLog
{
    public static function run($startTime, $verbosity)
    {
        if (!$verbosity) {
            return;
        }

        $peakMemory = self::getHumanSize(memory_get_peak_usage());
        $duration = self::getHumanDuration($startTime);

        echo "\e[93mMemory\e[0m: $peakMemory";
        echo PHP_EOL;
        echo "\e[93mTime\e[0m: $duration seconds";
        echo PHP_EOL;
    }

    /**
     * @param int $size
     * @return string
     */
    public static function getHumanSize(int $size): string
    {
        $magnitudes = ['bytes', 'Kib', 'Mib', "Gib", "Tib"];
        $magnitude = min((int)log($size, 1024), count($magnitudes) - 1);
        $argument = (float)$size / pow(1024, $magnitude);

        $precision = 2;
        if (!$magnitude) {
            $precision = 0;
        }

        $humanPeakMemory = sprintf("%0.{$precision}f ", $argument) . $magnitudes[$magnitude];

        return $humanPeakMemory;
    }

    /**
     * @param $startTime
     * @return mixed
     */
    public static function getHumanDuration($startTime): string
    {
        $defaultPrecision = 2;
        $duration = microtime(true) - $startTime;
        $precision = max($defaultPrecision, (-1)*floor(log($duration, 10)));
        return sprintf("%0.{$precision}f", $duration);
    }
}