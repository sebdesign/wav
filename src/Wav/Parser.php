<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Wav;

use Binary\Helper;
use Wav\Exception\FileIsNotExistsException;
use Wav\Exception\FileIsNotReadableException;
use Wav\Exception\FileIsNotWavFileException;
use Wav\File\DataSection;
use Wav\File\FormatSection;
use Wav\File\Header;

class Parser
{
    /**
     * @throws \Wav\Exception\FileIsNotExistsException
     * @throws \Wav\Exception\FileIsNotReadableException
     * @throws \Wav\Exception\FileIsNotWavFileException
     */
    public static function fromFile(string $filename): AudioFile
    {
        if (! file_exists($filename)) {
            throw new FileIsNotExistsException('File "' . $filename . '" is not exists.');
        }

        if (! is_readable($filename)) {
           throw new FileIsNotReadableException('File "' . $filename . '" is not readable"');
        }

        if (is_dir($filename)) {
            throw new FileIsNotWavFileException('File "' . $filename . '" is not a wav-file');
        }

        $size = filesize($filename);
        if ($size < AudioFile::HEADER_LENGTH) {
            throw new FileIsNotWavFileException('File "' . $filename . '" is not a wav-file');
        }

        $handle = fopen($filename, 'rb');

        if ($handle === false) {
            throw new FileIsNotReadableException('File "' . $filename . '" is not readable"');
        }

        try {
            $header         = Header::createFromArray(self::parseHeader($handle));
            $formatSection  = FormatSection::createFromArray(self::parseFormatSection($handle));
            $dataSection    = DataSection::createFromArray(self::parseDataSection($handle));
        } finally {
            fclose($handle);
        }

        return new AudioFile($header, $formatSection, $dataSection);
    }

    /**
     * @param resource $handle
     */
    protected static function parseHeader($handle): array
    {
        return [
            'id'     => Helper::readString($handle, 4),
            'size'   => Helper::readLong($handle),
            'format' => Helper::readString($handle, 4),
        ];
    }

    /**
     * @param resource $handle
     */
    protected static function parseFormatSection($handle): array
    {
        return [
            'id'               => Helper::readString($handle, 4),
            'size'             => Helper::readLong($handle),
            'audioFormat'      => Helper::readWord($handle),
            'numberOfChannels' => Helper::readWord($handle),
            'sampleRate'       => Helper::readLong($handle),
            'byteRate'         => Helper::readLong($handle),
            'blockAlign'       => Helper::readWord($handle),
            'bitsPerSample'    => Helper::readWord($handle),
        ];
    }

    /**
     * @param resource $handle
     */
    protected static function parseDataSection($handle): array
    {
        $data = [
            'id' => Helper::readString($handle, 4),
            'size' => Helper::readLong($handle),
        ];

        if ($data['size'] > 0) {
            $data['raw'] = fread($handle, $data['size']);
        }

        return $data;
    }
}
