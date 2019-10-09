<?php

namespace Wav;

use Binary\Helper;
use Wav\Exception\DirectoryIsNotWritableException;

/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */
class AudioFile
{
    const HEADER_LENGTH = 44;

    const BITS_PER_BYTE = 8;

    /**
     * @var \Wav\File\Header
     */
    protected $header;

    /**
     * @var \Wav\File\FormatSection
     */
    protected $formatSection;

    /**
     * @var \Wav\File\DataSection
     */
    protected $dataSection;

    /**
     * @var \Wav\Sample[]
     */
    protected $samples = [];

    /**
     * AudioFile constructor.
     */
    public function __construct(File\Header $header, File\FormatSection $formatSection, File\DataSection $dataSection)
    {
        $this->header        = $header;
        $this->formatSection = $formatSection;
        $this->dataSection   = $dataSection;
    }

    /**
     * @return \Wav\Sample[]
     */
    public function getAsSamples()
    {
        if (!$this->samples) {
            $raw          = $this->dataSection->getRaw();

            $sampleSize   = (int) ($this->formatSection->getBitsPerSample() / self::BITS_PER_BYTE);
            $samplesCount = strlen($raw) / $this->formatSection->getNumberOfChannels();

            for ($i = 0; $i < $samplesCount; $i++) {
                $this->samples[] = new Sample(
                    $sampleSize,
                    substr($raw, $i * $sampleSize, $sampleSize),
                    $i % $this->formatSection->getNumberOfChannels()
                );
            }
        }

        return $this->samples;
    }

    public function getAsAmplitudes(): array
    {
        $amplitudes = [];

        $volume = pow(2, $this->formatSection->getBitsPerSample()) / 2;

        foreach ($this->getAsSamples() as $sample) {
            $value = $sample->getValue();
            $amplitudes[] = $value / $volume;
        }

        return $amplitudes;
    }

    /**
     * @throws \Wav\Exception\DirectoryIsNotWritableException
     */
    public function saveToFile(string $filename): void
    {
        $directory = dirname($filename);

        if (!is_writable($directory)) {
            throw new DirectoryIsNotWritableException('Directory "' . $directory . '" is not writable');
        }

        $handle = fopen($filename, 'wb');

        if ($handle == false) {
            return;
        }

        $this->writeHeader($handle);
        $this->writeFormatSection($handle);
        $this->writeDataSection($handle);

        fclose($handle);
    }

    public function returnContent()
    {
        header('Content-Type: audio/wav');

        //return header
        echo Helper::packString($this->header->getId());
        echo Helper::packLong($this->header->getSize());
        echo Helper::packString($this->header->getFormat());

        //return format
        echo Helper::packString($this->formatSection->getId());
        echo Helper::packLong($this->formatSection->getSize());
        echo Helper::packWord($this->formatSection->getAudioFormat());
        echo Helper::packWord($this->formatSection->getNumberOfChannels());
        echo Helper::packLong($this->formatSection->getSampleRate());
        echo Helper::packLong($this->formatSection->getByteRate());
        echo Helper::packWord($this->formatSection->getBlockAlign());
        echo Helper::packWord($this->formatSection->getBitsPerSample());

        echo Helper::packString($this->dataSection->getId());
        echo Helper::packLong($this->dataSection->getSize());
        echo Helper::packString($this->dataSection->getRaw());

        exit;
    }

    /**
     * @param resource $handle
     */
    protected function writeHeader($handle): void
    {
        Helper::writeString($handle, $this->header->getId());
        Helper::writeLong($handle, $this->header->getSize());
        Helper::writeString($handle, $this->header->getFormat());
    }

    /**
     * @param resource $handle
     */
    protected function writeFormatSection($handle): void
    {
        Helper::writeString($handle, $this->formatSection->getId());
        Helper::writeLong($handle, $this->formatSection->getSize());
        Helper::writeWord($handle, $this->formatSection->getAudioFormat());
        Helper::writeWord($handle, $this->formatSection->getNumberOfChannels());
        Helper::writeLong($handle, $this->formatSection->getSampleRate());
        Helper::writeLong($handle, $this->formatSection->getByteRate());
        Helper::writeWord($handle, $this->formatSection->getBlockAlign());
        Helper::writeWord($handle, $this->formatSection->getBitsPerSample());
    }

    /**
     * @param resource $handle
     */
    protected function writeDataSection($handle): void
    {
        Helper::writeString($handle, $this->dataSection->getId());
        Helper::writeLong($handle, $this->dataSection->getSize());
        Helper::writeString($handle, $this->dataSection->getRaw());
    }

    public function getAudioFormat(): int
    {
        return $this->formatSection->getAudioFormat();
    }

    public function getNumberOfChannels(): int
    {
        return $this->formatSection->getNumberOfChannels();
    }

    public function getSampleRate(): int
    {
        return $this->formatSection->getSampleRate();
    }

    public function getByteRate(): int
    {
        return $this->formatSection->getByteRate();
    }

    public function getBlockAlign(): int
    {
        return $this->formatSection->getBlockAlign();
    }

    public function getBitsPerSample(): int
    {
        return $this->formatSection->getBitsPerSample();
    }
}
