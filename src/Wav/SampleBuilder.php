<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;


use Binary\Helper;
use Wav\Generator\Generator;

class SampleBuilder
{
    /**
     * @var \Wav\Generator\Generator
     */
    protected $generator;

    /**
     * @var int
     */
    protected $sampleRate = Builder::DEFAULT_SAMPLE_RATE;

    /**
     * @var int
     */
    protected $volume = Builder::DEFAULT_VOLUME;

    /**
     * SampleBuilder constructor.
     */
    public function __construct(string $name)
    {
        $this->generator = GeneratorFactory::getGenerator($name);
    }

    public function getGenerator(): Generator
    {
        return $this->generator;
    }

    public function setGenerator(Generator $generator): void
    {
        $this->generator = $generator;
    }

    public function getSampleRate(): int
    {
        return $this->sampleRate;
    }

    public function setSampleRate(int $sampleRate): void
    {
        $this->sampleRate = $sampleRate;
    }

    public function getVolume(): int
    {
        return $this->volume;
    }

    public function setVolume(int $volume): void
    {
        $this->volume = $volume;
    }

    public function note(string $note, int $octave, float $duration): Sample
    {
        $result = new \SplFixedArray((int) ceil($this->getSampleRate() * $duration * 2));

        /** @var int $octave */
        $octave = min(8, max(1, $octave));

        $frequency = Note::get($note) * pow(2, $octave - 4);

        $attack = $this->generator->getAttack($this->getSampleRate(), $frequency, $this->getVolume());
        $dampen = $this->generator->getDampen($this->getSampleRate(), $frequency, $this->getVolume());

        $attackLength = (int) ($this->getSampleRate() * $attack);
        $decayLength  = (int) ($this->getSampleRate() * $duration);

        for ($i = 0; $i < $attackLength; $i++) {
            $value = $this->getVolume()
                * ($i / ($this->getSampleRate() * $attack))
                * $this->getGenerator()->getWave(
                    $this->getSampleRate(),
                    $frequency,
                    $this->getVolume(),
                    $i
                );

            $result[$i << 1]       = Helper::packChar($value);
            $result[($i << 1) + 1] = Helper::packChar($value >> 8);
        }

        for (; $i < $decayLength; $i++) {
            $value = $this->getVolume()
                * pow((1 - (($i - ($this->getSampleRate() * $attack)) / ($this->getSampleRate() * ($duration - $attack)))), $dampen)
                * $this->getGenerator()->getWave(
                    $this->getSampleRate(),
                    $frequency,
                    $this->getVolume(),
                    $i
                );

            $result[$i << 1]       = Helper::packChar($value);
            $result[($i << 1) + 1] = Helper::packChar($value >> 8);
        }

        return new Sample($result->getSize(), implode('', $result->toArray()));
    }
}
