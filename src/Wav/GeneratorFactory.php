<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;


use Wav\Exception\UnknownGenerator;
use Wav\Generator\AcousticGuitar;
use Wav\Generator\Generator;
use Wav\Generator\Organ;
use Wav\Generator\Piano;

class GeneratorFactory
{
    public static function getPianoGenerator(): Piano
    {
        return new Piano();
    }

    public static function getAcousticGuitarGenerator(): AcousticGuitar
    {
        return new AcousticGuitar();
    }

    public static function getOrganGenerator(): Organ
    {
        return new Organ();
    }

    /**
     * @throws \Wav\Exception\UnknownGenerator
     */
    public static function getGenerator(string $name): Generator
    {
        switch ($name) {
            case Piano::NAME:
                return self::getPianoGenerator();
            case AcousticGuitar::NAME:
                return self::getAcousticGuitarGenerator();
            case Organ::NAME:
                return self::getOrganGenerator();
        }

        throw new UnknownGenerator('Unknown generator "' . $name . '"');
    }
}
