<?php
namespace sample1\helpers;

/**
 * Works with base64 encoded string with or without meta part
 *
 * Class Base64StringHelper
 * @package sample1\helpers
 */
class Base64StringHelper
{
    const BASE64_PATTERN = /** @lang text */
        "/^(?<meta>data:(?<type>[\w]+)\/(?<ext>[\w]+);base64,)?(?<encodedString>[a-zA-Z0-9\/+=]+)$/";

    /**
     * @param string|null $base64EncodedString
     * @return string|null
     */
    public static function getContentType(?string $base64EncodedString): ?string
    {
        list($meta) = explode(';base64,', $base64EncodedString);
        $meta = str_replace('data:', '', $meta);
        return $meta;
    }

    /**
     * @param string|null $base64EncodedString
     * @return string|null
     */
    public static function getMeta(?string $base64EncodedString): ?string
    {
        list($meta, $content) = explode(',', $base64EncodedString);
        return $meta !== null && $content !== null ? $meta : null;
    }

    /**
     * @param string|null $base64EncodedString
     * @return string|null
     */
    public static function stripMeta(?string $base64EncodedString): ?string
    {
        return (($commaPos = strpos($base64EncodedString, ',')) === false) ? $base64EncodedString : substr($base64EncodedString, $commaPos + 1);
    }

    /**
     * @param string|null $base64EncodedString
     * @return string
     */
    public static function stripMetaAndDecode(?string $base64EncodedString): ?string
    {
        $decoded = base64_decode(self::stripMeta($base64EncodedString), true);
        return $decoded ?: null;
    }

    /**
     * @param string $path
     * @return string|null
     */
    public static function getRawFileContent(string $path): ?string
    {
        $encodedContent = FileHelper::getFileContent($path);
        return Base64StringHelper::stripMetaAndDecode($encodedContent);
    }

    /**
     * @param string $base64EncodedString
     * @param bool $isMetaDataRequired
     * @return array
     */
    public static function validate(string $base64EncodedString, bool $isMetaDataRequired = true): array
    {
        $errors = [];
        if (!preg_match(self::BASE64_PATTERN, $base64EncodedString, $matches)) {
            $errors[] = 'Invalid Base64 string';
        }
        if ($matches) {
            if ($matches['encodedString'] === '') {
                $errors[] = 'Data part of Base64 string is empty or invalid';
            }
            if ($matches['meta'] === '' && $isMetaDataRequired) {
                $errors[] = 'Meta data is missing';

            }
        }
        return $errors;
    }

    /**
     * @param string $url
     * @return string
     */
    public static function getBase64StringFromUrl(string $url): ?string
    {
        /** @see https://stackoverflow.com/questions/26148701/file-get-contents-ssl-operation-failed-with-code-1-and-more */
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];

        $fileContent = file_get_contents($url, false, stream_context_create($arrContextOptions));
        return $fileContent !== false ? base64_encode($fileContent) : null;
    }
}
