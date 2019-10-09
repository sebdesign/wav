<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\File;


use Wav\Exception\InvalidWavDataException;

class FormatSection
{
    const FMT = 'fmt ';

    const SECTION_SIZE = 16;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var int
     */
    protected $audioFormat;

    /**
     * @var int
     */
    protected $numberOfChannels;

    /**
     * @var int
     */
    protected $sampleRate;

    /**
     * @var int
     */
    protected $byteRate;

    /**
     * @var int
     */
    protected $blockAlign;

    /**
     * @var int
     */
    protected $bitsPerSample;

    public static function createFromParameters(array $data): self
    {
        $chunk = new FormatSection();

        $chunk->id = self::FMT;
        $chunk->size = self::SECTION_SIZE;
        $chunk->audioFormat = $data['audioFormat'];
        $chunk->numberOfChannels = $data['numberOfChannels'];
        $chunk->sampleRate = $data['sampleRate'];
        $chunk->byteRate = $data['byteRate'];
        $chunk->blockAlign = $data['blockAlign'];
        $chunk->bitsPerSample = $data['bitsPerSample'];

        return $chunk;
    }

    /**
     * @throws \Wav\Exception\InvalidWavDataException
     */
    public static function createFromArray(array $data): self
    {
        $chunk = new FormatSection();

        $chunk->id = $data['id'];
        $chunk->size = $data['size'];
        $chunk->audioFormat = $data['audioFormat'];
        $chunk->numberOfChannels = $data['numberOfChannels'];
        $chunk->sampleRate = $data['sampleRate'];
        $chunk->byteRate = $data['byteRate'];
        $chunk->blockAlign = $data['blockAlign'];
        $chunk->bitsPerSample = $data['bitsPerSample'];

        if ($chunk->id !== self::FMT) {
            throw new InvalidWavDataException('Format section ID is not "fmt "');
        }

        return $chunk;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getAudioFormat(): int
    {
        return $this->audioFormat;
    }

    public function getNumberOfChannels(): int
    {
        return $this->numberOfChannels;
    }

    public function getSampleRate(): int
    {
        return $this->sampleRate;
    }

    public function getByteRate(): int
    {
        return $this->byteRate;
    }

    public function getBlockAlign(): int
    {
        return $this->blockAlign;
    }

    public function getBitsPerSample(): int
    {
        return $this->bitsPerSample;
    }
}
