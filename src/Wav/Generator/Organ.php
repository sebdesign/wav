<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\Generator;


class Organ extends Generator
{
    const NAME = 'organ';

    const ATTACK = 0.3;

    public function getName(): string
    {
        return self::NAME;
    }

    public function getAttack(?int $sampleRate = null, ?float $frequency = null, ?int $volume = null): float
    {
        return self::ATTACK;
    }

    public function getDampen(?int $sampleRate = null, ?float $frequency = null, ?int $volume = null): float
    {
        return 1 + ($frequency * 0.01);
    }

    public function getWave(int $sampleRate, float $frequency, int $volume, int $i): float
    {
        $base = $this->getModulations()[0];

        return call_user_func_array($base, [
            $i,
            $sampleRate,
            $frequency,
            call_user_func_array($base, [$i, $sampleRate, $frequency, 0]) +
            0.5 * call_user_func_array($base, [$i, $sampleRate, $frequency, 0.25]) +
            0.25 * call_user_func_array($base, [$i, $sampleRate, $frequency, 0.5])
        ]);
    }

}
