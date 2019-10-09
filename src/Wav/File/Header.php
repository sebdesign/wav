<?php

namespace Wav\File;

use Wav\Exception\InvalidWavDataException;

/**
 * Header structure of wav-file
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @package Wav\File
 */
class Header
{
    const RIFF = 'RIFF';
    const WAVE = 'WAVE';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $format;

    public static function createFromDataSection(DataSection $section): self
    {
        $header = new Header();

        $header->id     = self::RIFF;
        $header->size   = 40 + $section->getSize();
        $header->format = self::WAVE;

        return $header;
    }

    /**
     * @throws \Wav\Exception\InvalidWavDataException
     */
    public static function createFromArray(array $data): self
    {
        $header = new Header();

        $header->id = $data['id'];
        $header->size = $data['size'];
        $header->format = $data['format'];

        if ($header->id !== self::RIFF) {
            throw new InvalidWavDataException('Header ID is not "RIFF"');
        }

        if ($header->format !== self::WAVE) {
            throw new InvalidWavDataException('Header format is not "WAVE"');
        }

        return $header;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
