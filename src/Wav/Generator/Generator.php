<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\Generator;


abstract class Generator
{
    /**
     * @return callable[]
     */
    protected function getModulations(): array
    {
        return [
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 1 * sin(2 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 1 * sin(4 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 1 * sin(8 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 1 * sin(0.5 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 1 * sin(0.25 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 0.5 * sin(2 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 0.5 * sin(4 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 0.5 * sin(8 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 0.5 * sin(0.5 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
            function(int $i, int $sampleRate, float $frequency, $x) {
                return 0.5 * sin(0.25 * M_PI * (($i / $sampleRate) * $frequency) + $x);
            },
        ];
    }

    abstract public function getName(): string;

    abstract public function getAttack(?int $sampleRate = null, ?float $frequency = null, ?int $volume = null): float;

    abstract public function getDampen(?int $sampleRate = null, ?float $frequency = null, ?int $volume = null): float;

    abstract public function getWave(int $sampleRate, float $frequency, int $volume, int $i): float;
}
