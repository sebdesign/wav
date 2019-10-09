<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav\File;


use Wav\Exception\InvalidWavDataException;

class DataSection extends Section
{
    const DATA = 'data';

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
    protected $raw;

    public static function createFromRaw(string $raw): self
    {
        $chunk = new DataSection();

        $chunk->id   = self::DATA;
        $chunk->size = strlen($raw);
        $chunk->raw  = $raw;

        return $chunk;
    }

    /**
     * @throws \Wav\Exception\InvalidWavDataException
     */
    public static function createFromArray(array $data): self
    {
        $chunk = new DataSection();

        $chunk->id   = $data['id'];
        $chunk->size = $data['size'];
        $chunk->raw  = $data['raw'];

        if ($chunk->id !== self::DATA) {
            throw new InvalidWavDataException('Data section ID is not "data"');
        }

        return $chunk;
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
