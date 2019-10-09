<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;

use Wav\File\DataSection;
use Wav\File\FormatSection;
use Wav\File\Header;

class Builder
{
    const DEFAULT_SAMPLE_RATE = 44100;

    const DEFAULT_VOLUME = 32768;

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

    /**
     * @var \Wav\Sample[]
     */
    protected $samples;

    public function setAudioFormat(int $audioFormat = WaveFormat::PCM): self
    {
        $this->audioFormat = $audioFormat;

        return $this;
    }

    public function setNumberOfChannels(int $numberOfChannels = 1): self
    {
        $this->numberOfChannels = $numberOfChannels;

        return $this;
    }

    public function setSampleRate(int $sampleRate): self
    {
        $this->sampleRate = $sampleRate;

        return $this;
    }

    public function setByteRate(int $byteRate): self
    {
        $this->byteRate = $byteRate;

        return $this;
    }

    public function setBlockAlign(int $blockAlign): self
    {
        $this->blockAlign = $blockAlign;

        return $this;
    }

    public function setBitsPerSample(int $bitsPerSample): self
    {
        $this->bitsPerSample = $bitsPerSample;

        return $this;
    }

    /**
     * @param \Wav\Sample[] $samples
     */
    public function setSamples(array $samples): self
    {
        $this->samples = $samples;

        return $this;
    }

    public function build(): AudioFile
    {
        $raw = '';

        foreach ($this->samples as $sample) {
            $raw .= $sample->getData();
        }

        $data = DataSection::createFromRaw($raw);

        $format = FormatSection::createFromParameters([
            'audioFormat' => $this->audioFormat,
            'numberOfChannels' => $this->numberOfChannels,
            'sampleRate' => $this->sampleRate,
            'byteRate' => $this->byteRate,
            'blockAlign' => $this->blockAlign,
            'bitsPerSample' => $this->bitsPerSample,
        ]);

        $header = Header::createFromDataSection($data);

        return new AudioFile($header, $format, $data);
    }
}
