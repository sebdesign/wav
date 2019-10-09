<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\Generator;


class AcousticGuitar extends Generator
{
    const NAME = 'acoustic';

    const ATTACK = 0.002;

    private $values = [];

    private $periodCount = 0;

    private $playValue = 0;

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
        return 1.0;
    }

    public function getWave(int $sampleRate, float $frequency, int $volume, int $i): float
    {
        $period = $sampleRate / $frequency;

        if (count($this->values) <= $period) {
            $value = round(random_int(0, 1)) * 2 - 1;
            $this->values[] = $value;

            return $value;
        }

        $periodMultiplied = (int) floor(($period - floor($period)) * 100);

        $resetPlay = false;

        $index = ($this->playValue >= (count($this->values) - 1)) ? 0 : $this->playValue + 1;

        if (array_key_exists($this->playValue, $this->values)) {
            $this->values[$this->playValue] = ($this->values[$index] + $this->values[$this->playValue]) * 0.5;
        } else {
            $this->values[$this->playValue] = ($this->values[$index] + $this->values[$index + 1]) * 0.5;
        }

        if ($this->playValue >= floor($period)) {
            if ($this->playValue < ceil($period)) {
                if ($this->periodCount % 100 >= $periodMultiplied) {
                    $resetPlay = true;
                    $this->values[$this->playValue + 1] = ($this->values[0] + $this->values[$this->playValue + 1]) * 0.5;
                    $this->periodCount++;
                }
            } else {
                $resetPlay = true;
            }
        }

        $result = $this->values[$this->playValue];

        if ($resetPlay) {
            $this->playValue = 0;
        } else {
            $this->playValue++;
        }

        return $result;
    }
}
