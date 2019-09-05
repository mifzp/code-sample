<?php
namespace sample1\helpers;

use sample1\exceptions\CreateFileException;

/**
 * Extended file system helper.
 *
 * Class Base64StringHelper
 * @package sample1\helpers
 */
class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * @param string $path
     * @return string|nulL
     */
    public static function getFileContent(string $path): ?string
    {
        if (!file_exists($path)) {
            return null;
        }
        return file_get_contents($path);
    }

    /**
     * @param string $path
     * @param $value
     * @param bool $needDecode
     * @throws CreateFileException
     */
    public static function setFileContent(string $path, $value, $needDecode = false): void
    {
        if (empty($value)) {
            return;
        }

        if ($needDecode) {
            $value = Base64StringHelper::stripMeta($value);
            $value = base64_decode($value);
        }

        if (!file_put_contents($path, $value)) {
            throw new CreateFileException("File saving failed '$path'");
        }
    }

    /**
     * @param string $path
     * @return int|null
     */
    public static function getFileSize(string $path): ?int
    {
        if (!file_exists($path)) {
            return null;
        }
        return filesize($path);
    }
}
