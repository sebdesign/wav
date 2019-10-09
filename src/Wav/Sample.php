<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;

class Sample
{
    /**
     * @var int
     */
    protected $sampleSize;

    /**
     * @var int
     */
    protected $channel;

    /**
     * @var string
     */
    protected $data;

    /**
     * Sample constructor.
     */
    public function __construct(int $sampleSize, string $data, int $channel = 1)
    {
        $this->sampleSize = $sampleSize;
        $this->data       = $data;
        $this->channel    = $channel;
    }

    public function getSampleSize(): int
    {
        return $this->sampleSize;
    }

    public function getChannel(): int
    {
        return $this->channel;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getValue(): int
    {
        switch ($this->sampleSize) {
            case 8:
                $value = current(unpack('C', $this->data)); // unsigned char [0, 256]
                return $this->convertToSigned($value, $this->sampleSize);
            case 16:
                $values = unpack('Clow/chigh', $this->data); // unsigned char / signed char, little endian [0, 65536]
                return ($values['high'] << 8) + $values['low'];
            case 24:
                $values = unpack('Clow/Cmid/chigh', $this->data); // unsigned char / unsigned chart / signed char, little endian [0, 16777216]
                return ($values['high'] << 16) + ($values['mid'] << 8) + $values['low'];
            case 32:
                $values = unpack("vlow/vhigh", $this->data); // signed long / signed long, little endian [0, 4294967296]
                return ($this->convertToSigned($values['high'], 16) << 16) + $values['low'];
            default:
                return 0;
        }
    }

    protected function convertToSigned(int $value, int $size): int
    {
        if ($value >= pow(2, $size - 1)) {
            return (int) ($value - pow(2, $size));
        }

        return $value;
    }
}
