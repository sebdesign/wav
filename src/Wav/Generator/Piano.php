<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\Generator;

class Piano extends Generator
{
    /**
     * @var string Name of piano generator
     */
    const NAME = 'piano';

    /**
     * @var float Attack for piano generator
     */
    const ATTACK = 0.002;

    public function getName(): string
    {
        return self::NAME;
    }

    public function getAttack(?int $sampleRate = null, ?float $frequency = null, $volume = null): float
    {
        return self::ATTACK;
    }

    public function getDampen(?int $sampleRate = null, ?float $frequency = null, $volume = null): float
    {
        return pow(0.5 * log(($frequency * $volume) / $sampleRate), 2);
    }

    public function getWave(int $sampleRate, float $frequency, int $volume, int $i): float
    {
        $base = $this->getModulations()[0];

        return call_user_func_array($base, [
            $i,
            $sampleRate,
            $frequency,
            pow(call_user_func_array($base, [$i, $sampleRate, $frequency, 0]), 2) +
            0.75 * call_user_func_array($base, [$i, $sampleRate, $frequency, 0.25]) +
            0.1 * call_user_func_array($base, [$i, $sampleRate, $frequency, 0.5])
        ]);
    }
}
