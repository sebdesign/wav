<?php
/**
 * @author  Nikita Kolosov <anexroid@gmail.com>
 * @license MIT License
 * @year    2016
 */

namespace Binary;

class Helper
{
    /**
     * @param resource $handle
     */
    public static function readString($handle, int $length): string
    {
        return self::readUnpacked($handle, 'a*', $length);
    }

    /**
     * @return string|false
     */
    public static function packString($data)
    {
        return self::pack('a*', $data);
    }

    /**
     * @param resource $handle
     */
    public static function writeString($handle, $data): int
    {
        return self::writeUnpacked($handle, 'a*', $data);
    }

    /**
     * @param resource $handle
     */
    public static function readLong($handle): int
    {
        return self::readUnpacked($handle, 'V', 4);
    }

    /**
     * @return string|false
     */
    public static function packLong($data)
    {
        return self::pack('V', $data);
    }

    /**
     * @param resource $handle
     */
    public static function writeLong($handle, $data): int
    {
        return self::writeUnpacked($handle, 'V', $data);
    }

    /**
     * @param resource $handle
     */
    public static function readWord($handle): int
    {
        return self::readUnpacked($handle, 'v', 2);
    }

    /**
     * @return string|false
     */
    public static function packWord($data)
    {
        return self::pack('v', $data);
    }

    /**
     * @param resource $handle
     */
    public static function writeWord($handle, $data): int
    {
        return self::writeUnpacked($handle, 'v', $data);
    }

    /**
     * @return string|false
     */
    public static function packChar($data)
    {
        return self::pack('c', $data);
    }

    /**
     * @param resource $handle
     */
    protected static function readUnpacked($handle, string $type, int $length)
    {
        $data = unpack($type, fread($handle, $length));

        return array_pop($data);
    }

    /**
     * @param resource $handle
     */
    protected static function writeUnpacked($handle, string $type, $data): int
    {
        return fwrite($handle, self::pack($type, $data));
    }

    /**
     * @return string|false
     */
    protected static function pack(string $type, $data)
    {
        return pack($type, $data);
    }
}
